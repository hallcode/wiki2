<?php

namespace App\Parsers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InfoBoxParser
{
    protected string $rawText;
    protected string $bodyText;
    protected string $infoBoxMarkup = "";
    protected bool $returnInfoBox;
    protected string $infoboxPattern = "/<InfoBox(.+)>(.*?)<\/InfoBox>/msUi";

    public function __construct(string $rawText, $infoBox = false)
    {
        $this->rawText = $rawText;
        $this->returnInfoBox = $infoBox;
    }

    protected function extractMarkup()
    {
        if (!$this->returnInfoBox) {
            // We're parsing the page so we only need the clean body text.
            $this->bodyText = preg_replace(
                $this->infoboxPattern,
                "",
                $this->rawText
            );
            return;
        }

        // If we need the infobox too, then we better get that text:
        $matches = [];
        preg_match_all(
            $this->infoboxPattern,
            $this->rawText,
            $matches,
            PREG_PATTERN_ORDER
        );

        foreach ($matches[0] as $m) {
            $this->infoBoxMarkup .= $m;
        }
    }

    protected function padContents(string $contents): string
    {
        return PHP_EOL . PHP_EOL . $contents . PHP_EOL . PHP_EOL;
    }

    protected function renderInfoBox(array $matches): string
    {
        $header = $this->renderHeader($matches[1]);
        return "<table class=\"infobox\">$header<tbody>$matches[2]</tbody></table>";
    }

    protected function renderHeader(string $attrs): string
    {
        if (empty($attrs)) {
            return "";
        }

        $markup = "";
        $attrPattern = '/([a-zA-Z]+)="([^"<>]+)"/siUm';
        $matches = [];
        preg_match_all($attrPattern, $attrs, $matches, PREG_SET_ORDER);

        foreach ($matches as $attr) {
            if ($attr[1] == "title") {
                $markup .= "<tr><th colspan=\"2\"><h1>$attr[2]</h1></th></tr>";
            }

            if ($attr[1] == "caption") {
                $markup .= "<tr><td colspan=\"2\" class=\"caption\">$attr[2]</td></tr>";
            }

            if ($attr[1] == "img") {
                $url = route("media.thumb", [
                    "slug" => urlencode(Str::apa($attr[2])),
                    "size" => 300,
                ]);
                $linkUrl = route("media.view", [
                    "slug" => urlencode(Str::apa($attr[2])),
                ]);
                $markup .= "<tr><th colspan=\"2\"><a href=\"$linkUrl\"><img src=\"$url\" /></a></th></tr>";
                continue;
            }
        }

        return "<thead>" . $markup . "</thead>";
    }

    protected function renderTitle(array $matches): string
    {
        $content = trim($matches[2]);
        return "<tr><th colspan=\"2\" class=\"level_$matches[1]\">$content</th></tr>";
    }

    protected function renderField(array $matches): string
    {
        $content = $this->padContents($matches[2]);
        return "<tr class=\"field\"><th>$matches[1]</th><td>$content</td></tr>";
    }

    protected function renderAnythingElse(string $tag, string $contents): string
    {
        $content = $this->padContents($contents);

        if (strtolower($tag) == "title") {
            return "<tr><th colspan=\"2\">$contents</th></tr>";
        }

        return "<tr><td colspan=\"2\">$content</td></tr>";
    }

    public function parse(): string
    {
        $this->extractMarkup();

        if (!$this->returnInfoBox) {
            return $this->bodyText;
        }

        $this->infoBoxMarkup = preg_replace_callback_array(
            [
                $this->infoboxPattern => fn($m) => $this->renderInfoBox($m),
                '/<Title level="([0-9]+)">(.+)<\/Title>/msUi' => fn(
                    $m
                ) => $this->renderTitle($m),
                '/<Field title="([^<>\/"]+)">(.+)<\/Field>/msUi' => fn(
                    $m
                ) => $this->renderField($m),
                "/<(Field|p|Title)>([^<>]+)<\/(?:Field|Title|p)>/msUi" => fn(
                    $m
                ) => $this->renderAnythingElse($m[1], $m[2]),
            ],
            $this->infoBoxMarkup
        );

        return $this->infoBoxMarkup;
    }
}
