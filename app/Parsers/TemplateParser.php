<?php

namespace App\Parsers;

use Symfony\Component\Yaml\Yaml;
use Mustache_Engine;
use App\Models\Page;
use Str;

class TemplateParser
{
    protected string $rawText;
    protected array $context = [];
    protected string $tagRegex = "/\<(?:Template)\:([^\<\>]+)\>([^\<\>]*)\<(?:\/Template)\>/";

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    protected function parseContext($yaml): array
    {
        if (!$yaml) {
            return [];
        }

        return Yaml::parse($yaml);
    }

    protected function renderTemplate($name): string
    {
        $page = Page::where("title", Str::apa($name))
            ->where("is_template", true)
            ->first();

        if ($page == null) {
            return "<em>!Template: '" . $name . "' not found!</em>";
        }

        $mustache = new Mustache_Engine();
        $content = $mustache->render(
            $page->currentVersion->content,
            $this->context
        );
        $parser = new TemplateParser($content);
        return $parser->parse();
    }

    public function parse(): string
    {
        return preg_replace_callback(
            $this->tagRegex,
            function ($m) {
                $templateName = $m[1];
                $yaml = $m[2];
                $this->context = $this->parseContext($yaml);
                return $this->renderTemplate($templateName);
            },
            $this->rawText
        );
    }
}
