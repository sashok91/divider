<?php


class Divider
{
    protected $source;
    protected $dom;
    protected $htmlParts = [];
    protected $minPartLength;

    public function __construct($source)
    {
        $this->source = $source;

        $this->dom = new DOMDocument();
        // create dom with 'html' and 'body' nodes
        $this->dom->loadHTML($this->source);

        // minimum length of the contenten for the first column is half of whole content length
        $this->minPartLength = round($this->calcStrLength($this->source) / 2);
    }

    protected function divide()
    {
        // for elements of the first part
        $document1 = new DOMDocument();

        // for elements of the second part
        $document2 = new DOMDocument();

        // add image to the second column and remove it from source dom
        $img = $this->findFirstAndRemove('img');
        if ($img) {
            $document2->appendChild($document2->importNode($img, true));
        }

        $lengthOfFirstPart = 0;

        $current = $this->dom->documentElement->firstChild->firstChild;

        // add elements to the first column
        while ($current && $lengthOfFirstPart < $this->minPartLength) {
            $lengthOfFirstPart += $this->getNodeLength($current);

            $document1->appendChild($document1->importNode($current, true));

            $current = $current->nextSibling;
        }

        // add elements to the second column
        while ($current) {
            $document2->appendChild($document2->importNode($current, true));
            $current = $current->nextSibling;
        }

        array_push($this->htmlParts, $document1->saveHTML());
        array_push($this->htmlParts, $document2->saveHTML());

        return $this->htmlParts;
    }

    protected function getNodeLength($node)
    {
        $currentNodeLength = 0;
        if ($node) {
            switch ($node->nodeType) {
                case 1: // element
                    $currentNodeLength = $this->calcStrLength($this->getInnerHTML($node));
                    break;
                case 3: // text
                    $str = trim(html_entity_decode($node->textContent));
                    $currentNodeLength = strlen($str);
                    break;
                default:
                    $currentNodeLength = 0;
                    break;
            }
        }
        return $currentNodeLength;
    }

    // It is assumed that the image can be nested in another element. So we look for all parents of the element until the 'body'.
    // Then we move the element with the parent.
    protected function findFirstAndRemove($tagName)
    {
        $result = null;
        $nodes = $this->dom->getElementsByTagName($tagName);
        if (isset($nodes[0])) {
            $current = $nodes[0];
            while ($current->parentNode && $current->parentNode->nodeName != 'body') {
                $current = $current->parentNode;
            }
            $result = $current->parentNode->removeChild($current);

        }
        return $result;
    }

    public function getParts()
    {
        return $this->htmlParts;
    }

    // calculate length of content withot tags
    protected function calcStrLength($htmlString)
    {
        $length = 0;

        if ($htmlString) {
            // remove all tags from html string
            $newStr = strip_tags($htmlString);
            $length = strlen($newStr);
        }
        return $length;
    }

    protected function getInnerHTML($node)
    {
        $document = new DOMDocument();
        $document->appendChild($document->importNode($node, true));
        return $document->saveHTML();
    }


}