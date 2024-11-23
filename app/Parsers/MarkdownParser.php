<?php

namespace App\Parsers;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownParser
{
    protected string $rawText;
    protected $environment;
    protected $config = [];

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
        $this->environment = new Environment($this->config);

        // Extensions
        $this->environment->addExtension(new CommonMarkCoreExtension());
    }

    /**
     * @return string
     */
    public function parse(): string
    {
        $converter = new MarkdownConverter($this->environment);
        return $converter->convert($this->rawText);
    }
}
