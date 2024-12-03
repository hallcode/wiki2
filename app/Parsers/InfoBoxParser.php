<?php

namespace App\Parsers;

use DOMDocument, DOMElement;
use Illuminate\Support\Facades\DB;

class InfoBoxParser
{
    protected string $rawText;
    protected string $bodyText;
    protected DOMDocument $dom;
    protected bool $returnInfoBox;
    protected array $infoBoxes = [];

    // Options
    private $infoBoxTag = "InfoBox";

    public function __construct(string $rawText, $infoBox = false)
    {
        $this->rawText = $rawText;
        $this->returnInfoBox = $infoBox;
    }

    protected function extractMarkup()
    {
        if (!$this->returnInfoBox) {
            // We're parsing the page so we only need the clean body text.
            $this->bodyText = preg_replace(
                "/<InfoBox\b[^>]*>.*?<\/InfoBox>/s",
                "",
                $this->rawText
            );
            return;
        }

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = true;
        if (!$dom->loadXML("<root>{$this->rawText}</root>")) {
            throw new \RuntimeException(
                "Failed to parse XML: Input might be invalid."
            );
        }

        $infoBoxes = $dom->getElementsByTagName($this->infoBoxTag);
        foreach ($infoBoxes as $infoBox) {
            $this->infoBoxes[] = $infoBox;
        }
    }

    protected function renderInfoBox(DOMElement $element)
    {
        $dom = $this->dom;
        $dom->preserveWhiteSpace = true;

        $infobox = $dom->createElement("article");
        $infobox->setAttribute("class", "infobox");

        // Header
        $infobox->appendChild($this->renderHeader($element));

        // All the children
        foreach ($element->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            if ($child->nodeName === "Title") {
                $level = $child->getAttribute("level") ?? "1";
                $el = $level == "1" ? "h1" : "h2";
                $new = $dom->createElement($el, $child->textContent);
                $infobox->appendChild($new);
                continue;
            }

            if ($child->nodeName === "Field") {
                $title = $child->getAttribute("title");
                $el = $dom->createElement("h3", $title);
                $infobox->appendChild($el);

                // Get child nodes and create a container element
                $in = $dom->importNode($child, true);
                $in->normalize();
                $content = $dom->createElement("section");
                $content->setAttribute("class", "field-content");

                // Create and prepend two EOL nodes, this is to ensure that the Markdown
                // Parser understands that the content should be treated as a new paragraph.
                $eol = $dom->createTextNode(PHP_EOL);
                $content->append($eol, $eol, ...$in->childNodes);
                $infobox->appendChild($content);
                continue;
            }

            $in = $dom->importNode($child, true);
            $infobox->appendChild($in);
        }

        $dom->appendChild($infobox);
        $html = $dom->saveHTML();
        $html = str_replace(["<root>", "</root>"], "", $html);

        return $html;
    }

    protected function renderHeader(DOMElement $element)
    {
        // Header
        $dom = $this->dom;

        $header = $dom->createElement("header");
        if ($element->hasAttribute("title")) {
            $title = $dom->createElement("h1", $element->getAttribute("title"));
            $header->appendChild($title);
        }
        if ($element->hasAttribute("img")) {
            $img = $dom->createElement("img");

            $figure = $dom->createElement("figure");

            if ($element->hasAttribute("img")) {
                $img = $dom->createElement("img");
                $url = route("media.thumb", [
                    "slug" => urlencode($element->getAttribute("img")),
                    "size" => "230",
                ]);
                $img->setAttribute("src", $url);
                $figure->appendChild($img);
            }

            if ($element->hasAttribute("caption")) {
                $caption = $dom->createElement(
                    "figcaption",
                    $element->getAttribute("caption")
                );
                $figure->appendChild($caption);
            }

            $header->appendChild($figure);
        }

        return $header;
    }

    public function parse(): string
    {
        $this->extractMarkup();

        $this->dom = new DOMDocument();

        if ($this->returnInfoBox) {
            $infoBoxRender = "";
            foreach ($this->infoBoxes as $infoBoxElement) {
                $html = $this->renderInfoBox($infoBoxElement);
                $infoBoxRender .= $html;
            }
            return $infoBoxRender;
        }

        return $this->bodyText;
    }
}
