<?php

namespace App\Parsers;

use DOMDocument, DOMElement;
use Illuminate\Support\Facades\DB;
use DateTime;

class AgeParser
{
    protected string $rawText;

    // Options
    private $ageTag = "Age";

    public function __construct(string $rawText)
    {
        $this->rawText = $rawText;
    }

    public function parse(): string
    {
        $newText = $this->rawText;

        $newText = preg_replace_callback(
            "/\<(?:{$this->ageTag})\s?(show-date)?[^\<\>]+\>([^\<\>]+)\<\/(?:{$this->ageTag})\>/",
            function ($m) {
                $showDate = isset($m[1]);
                $date = $m[2];

                // Parse the date and calculate the age
                $birthDate = new DateTime($date);
                $currentDate = new DateTime();
                $age = $currentDate->diff($birthDate)->y;

                // Generate the replacement text
                if ($showDate) {
                    return $replacementText = $date . " (age $age)";
                }

                return $replacementText = $age;
            },
            $this->rawText
        );

        return $newText;

        libxml_use_internal_errors(true);
        $dom->loadXML($xml);

        // Loop through the tags
        $elements = $dom->getElementsByTagName($this->ageTag);
        foreach (iterator_to_array($elements) as $ageElement) {
            $dateText = $ageElement->textContent;
            $showDate = $ageElement->getAttribute("show-date") === "yes";

            // Parse the date and calculate the age
            $birthDate = new DateTime($dateText);
            $currentDate = new DateTime();
            $age = $currentDate->diff($birthDate)->y;

            // Generate the replacement text
            if ($showDate) {
                $replacementText = $dateText . " (age $age)";
            } else {
                $replacementText = $age;
            }

            // Replace node
            $newNode = $dom->createElement("span", $replacementText);
            $ageElement->replaceWith($newNode);
        }

        $html = $dom->saveHTML();
        $html = str_replace(["<root>", "</root>"], "", $html);
        return $html;
    }
}
