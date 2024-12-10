<?php

namespace App\Parsers;

use Illuminate\Support\Str;

class GalleryParser
{
    protected string $rawText;
    protected string $regex = '/<Gallery\s?(?:caption="([^\[\]<>"]+)")?\>(.+)<\/Gallery>/msUi';

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    protected function renderGallery(
        array $images,
        string $caption = ""
    ): string {
        $header = "";
        if (!empty($caption)) {
            $header = "<h5>$caption</h5>";
        }

        $imageMarkup = PHP_EOL . implode(PHP_EOL, $images) . PHP_EOL;

        return PHP_EOL .
            PHP_EOL .
            "$header<div class=\"gallery-wrapper\"><div class=\"gallery\">$imageMarkup</div></div>" .
            PHP_EOL .
            PHP_EOL;
    }

    protected function renderImages(string $galleryContents): array
    {
        $imageTags = [];

        // Iterate lines
        $separator = PHP_EOL;
        $line = strtok($galleryContents, $separator);

        while ($line !== false) {
            # do something with $line
            $line = strtok($separator);
            if (empty($line)) {
                continue;
            }

            $imageDeets = explode("|", $line);
            $title = trim($imageDeets[0]);
            $caption = trim($imageDeets[1] ?? "");
            $imageTags[] = $this->renderImage($title, $caption);
        }

        return $imageTags;
    }

    protected function renderImage(string $title, string $caption)
    {
        $url = route("media.view", ["slug" => urlencode(Str::apa($title))]);
        $imgSrc = route("media.thumb", [
            "slug" => urlencode(Str::apa($title)),
            "size" => 300,
        ]);
        $captionTag = empty($caption)
            ? ""
            : "<figcaption>$caption</figcaption>";
        return "<figure><a href=\"$url\"><img src=\"$imgSrc\" alt=\"$title\"/></a>$captionTag</figure>";
    }

    public function parse(): string
    {
        $newText = preg_replace_callback(
            $this->regex,
            function ($matches) {
                $images = $this->renderImages($matches[2] ?? "");
                return $this->renderGallery($images, $matches[1]);
            },
            $this->rawText
        );

        return $newText;
    }
}
