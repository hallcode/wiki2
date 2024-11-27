<?php

namespace App\Parsers;

use App\Parsers\LinkParser;
use App\Parsers\MarkdownParser;
use HTMLPurifier;
use HTMLPurifier_Config;

class PageParser
{
    protected string $rawText;

    protected bool $renderInfoBox;

    protected array $parsers = [
        TemplateParser::class,
        AgeParser::class,
        NoteParser::class,
        InfoBoxParser::class,
        LinkParser::class,
        MarkdownParser::class,
        HeadingParser::class,
    ];

    public array $headings = [];

    public function __construct(string $rawText, bool $infoBoxOnly = false)
    {
        $this->rawText = $rawText;
        $this->renderInfoBox = $infoBoxOnly;
    }

    private function cleanHTML($dirtyHtml): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set("Attr.EnableID", true);
        $purifier = new HTMLPurifier($config);

        $cleanHtml = $purifier->purify($dirtyHtml);
        return $cleanHtml;
    }

    public function parse(): string
    {
        $text = $this->rawText;

        foreach ($this->parsers as $parser) {
            try {
                $currentParser = new $parser($text, $this->renderInfoBox);
            } catch (Exception $e) {
                $currentParser = new $parser($text);
            }
            $text = $currentParser->parse();

            if (isset($currentParser->headings)) {
                $this->headings = $currentParser->headings;
            }
        }

        if (!$this->renderInfoBox) {
            $text = $this->cleanHTML($text);
        }

        return $text;
    }
}
