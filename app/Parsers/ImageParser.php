<?php

namespace App\Parsers;

use DOMDocument, DOMElement, DateTime, DOMXpath;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageParser
{
    protected string $rawText;

    protected $regex = "/\[\#([^\[\]|]+)\|?([0-9]+)?\|?([^\[\]|]+)?\]/";

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    protected function makeImgTag(
        string $imageName,
        string $size = "200",
        string $caption = ""
    ): string {
        if (empty($size)) {
            $size = "200";
        }

        $imageTag = $this->renderImg($size, $imageName);

        return PHP_EOL .
            $this->renderFigure(
                $caption,
                $this->renderLink($imageName, $imageTag, $size),
                $size
            ) .
            PHP_EOL .
            PHP_EOL;
    }

    protected function renderImg(string $size, string $imageName)
    {
        $url = route("media.thumb", [
            "slug" => urlencode(Str::apa($imageName)),
            "size" => $size,
        ]);

        return "<img src=\"$url\" />";
    }

    protected function renderLink(
        string $imageName,
        string $contents,
        string $size
    ) {
        $url = route("media.view", [
            "slug" => urlencode(Str::apa($imageName)),
        ]);
        return "<a href=\"$url\">$contents</a>";
    }

    protected function renderFigure(
        string $caption,
        string $contents,
        string $size
    ) {
        $captionTag = "";
        if (!empty($caption)) {
            $captionTag = "<figcaption>$caption</figcaption>";
        }
        return "<figure class=\"inline-image\" style=\"max-width: {$size}px\">{$contents}{$captionTag}</figure>";
    }

    public function parse(): string
    {
        $newText = preg_replace_callback(
            $this->regex,
            fn($m) => $this->makeImgTag($m[1], $m[2] ?? "200", $m[3] ?? ""),
            $this->rawText
        );

        return $newText;
    }
}
