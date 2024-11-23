<?php

namespace App\Parsers;

use DOMDocument, DOMElement, DateTime, DOMXpath;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HeadingParser
{
    protected string $rawText;
    public array $headings = [];

    protected $tags = ["h2", "h3"];

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    protected function makeRegex(): string
    {
        $tagString = implode("|", $this->tags);
        return "/\<($tagString)\>([^\<\>]++)\<\/(?:$tagString)\>/";
    }

    protected function makeId(string $headingText, string $prefix = "h")
    {
        $conformedText = Str::of($headingText)->stripTags()->snake();
        return $prefix . "__" . $conformedText;
    }

    protected function makeNewTag(string $tag, string $id, string $text): string
    {
        return "<{$tag} id=\"{$id}\">{$text}</{$tag}>";
    }

    public function parse(): string
    {
        $newText = preg_replace_callback(
            $this->makeRegex(),
            function ($m) {
                $heading = [
                    "id" => $this->makeId($m[2], $m[1]),
                    "text" => $m[2],
                    "level" => $m[1],
                ];

                $this->headings[] = $heading;

                return $this->makeNewTag(
                    $heading["level"],
                    $heading["id"],
                    $heading["text"]
                );
            },
            $this->rawText
        );

        return $newText;
    }
}
