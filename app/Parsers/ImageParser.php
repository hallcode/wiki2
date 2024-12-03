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

        $url = route("media.thumb", [
            "slug" => urlencode($imageName),
            "size" => $size,
        ]);
        $imgTag = "<img width=\"{$size}\" height=\"auto\" src=\"{$url}\" />";

        $captionTag = "";
        if (!empty($caption)) {
            $captionTag = "<figcaption>$caption</figcaption>";
        }

        $figure = "<figure class=\"inline-image\" style=\"max-width: {$size}px\">{$imgTag}{$captionTag}</figure>";
        $anchorUrl = route("media.view", ["slug" => urlencode($imageName)]);
        return "<a href=\"$anchorUrl\">$figure</a>" . PHP_EOL . PHP_EOL;
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
