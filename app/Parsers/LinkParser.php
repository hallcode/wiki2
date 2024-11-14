<?php

namespace App\Parsers;

class LinkParser
{
    protected string $rawText;

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    /**
     * Scans the rawText and returns an array containing details of the links found in the
     * text and if they point to an existant page.
     *
     * @return array
     */
    public function getLinks(): array
    {
        $rawText = $this->rawText;
        $links = [];
        $currentLink = 0;
        $onLink = false;
        for ($i = 1; isset($rawText[$i]); $i++) {
            $char = $rawText[$i];
            if ($char != "[" && !$onLink) {
                continue;
            }

            if ($char == "[" && $onLink) {
                continue;
            }

            if (!$onLink && $char == "[") {
                $onLink = true;
                $currentLink++;
                continue;
            }

            if ($onLink && $char == "]") {
                $onLink = false;
            }

            $links[$currentLink] += $char;
        }

        return $links;
    }
}
