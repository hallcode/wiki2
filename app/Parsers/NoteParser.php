<?php

namespace App\Parsers;

use Illuminate\Support\Facades\DB;

class NoteParser
{
    protected string $rawText;
    protected array $notes = [];
    protected string $noteRegex = "/\[note:(.+)\]/";
    protected string $citeRegex = "/\[cite:(.+)\]/";

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    protected function makeSupElement(array $note)
    {
        return '<sup class="ref" title="' .
            $note["text"] .
            '"><a href="#' .
            $note["type"] .
            "__" .
            $note["key"] .
            '">[' .
            $note["key"] .
            "]</a></sup>";
    }

    protected function makeListItem(array $note)
    {
        // Generate the link - if any
        $link = "";
        if ($note["link"] !== null) {
            if (
                str_starts_with($note["link"], "http") ||
                str_starts_with($note["link"], "www.")
            ) {
                // assume external link
                $link =
                    '<dd><a href="' .
                    $note["link"] .
                    '">' .
                    $note["link"] .
                    "</a></dd>";
            } else {
                // Internal link, output a wikilink and let the parser sort it out
                $link = "<dd>[[" . $note["link"] . "]]</dd>";
            }
        }

        // Start off the link item
        $listItem =
            '<li><dl><dt id="' .
            $note["type"] .
            "__" .
            $note["key"] .
            '" class="counter">[' .
            $note["key"] .
            "]</dt>" .
            "<dd>" .
            $note["text"] .
            "</dd>";

        if ($link != "") {
            $listItem .= $link;
        }

        // Add the ending tags and return
        return $listItem .= "</dl></li>";
    }

    protected function replace(string $type, string $content): string
    {
        $split = explode("|", $content);
        $element = [
            "key" => count($this->notes) + 1,
            "type" => $type,
            "text" => $split[0],
            "link" => $split[1] ?? null,
        ];
        $this->notes[] = $element;
        return $this->makeSupElement($element);
    }

    public function parse(): string
    {
        $rawText = $this->rawText;
        $newText = preg_replace_callback(
            $this->noteRegex,
            fn($match) => $this->replace("note", $match[1]),
            $rawText
        );
        $newText = preg_replace_callback(
            $this->citeRegex,
            fn($match) => $this->replace("cite", $match[1]),
            $newText
        );

        $noteSection = "<h2>Notes</h2><ol class=\"reference-list\">";
        $citeSection = "<h2>References</h2><ol class=\"reference-list\">";
        $nCount = 0;
        $cCount = 0;
        foreach ($this->notes as $note) {
            if ($note["type"] == "note") {
                $nCount += 1;
                $noteSection .= $this->makeListItem($note);
                continue;
            }
            $cCount += 1;
            $citeSection .= $this->makeListItem($note);
        }
        $noteSection .= "</ol>";
        $citeSection .= "</ol>";

        if ($nCount > 0) {
            $newText .= $noteSection;
        }

        if ($cCount > 0) {
            $newText .= $citeSection;
        }
        return $newText;
    }
}
