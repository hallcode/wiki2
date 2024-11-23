<?php

namespace App\Parsers;

use Illuminate\Support\Facades\DB;

class LinkParser
{
    protected string $rawText;
    protected string $linkRegex = "/\[\[([^\|\]\[]+)\|?([^\|\[\]]*)\]\]/";

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    /**
     * Scans the rawText and returns an array containing details of the links found in the
     * text.
     *
     * @return array
     */
    public function getLinks(): array
    {
        $links = [];
        preg_match_all(
            $this->linkRegex,
            $this->rawText,
            $links,
            PREG_SET_ORDER
        );

        return $links;
    }

    private function makeLink(
        string $targetTitle,
        string $displayText = null
    ): string {
        $url = route("page.view", ["slug" => urlencode($targetTitle)]);

        // Check if page exists
        $class = "";
        if (DB::table("pages")->where("title", $targetTitle)->count() < 1) {
            $class = ' class="redlink"';
        }

        if (!$displayText) {
            $displayText = $targetTitle;
        }
        return "<a href=\"{$url}\"{$class}>{$displayText}</a>";
    }

    public function parse(): string
    {
        $rawText = $this->rawText;
        $newText = preg_replace_callback(
            $this->linkRegex,
            fn($match) => $this->makeLink($match[1], $match[2]),
            $rawText
        );

        return $newText;
    }
}
