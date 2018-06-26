<?php

namespace App\Analyser;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler as BaseCrawler;

class Crawler extends BaseCrawler
{
    protected $rawHtml;

    /**
     * @param mixed $node A Node to use as the base for the crawling
     * @param string $currentUri The current URI
     * @param string $baseHref The base href value
     */
    public function __construct($node = null, $currentUri = null, $baseHref = null)
    {
        if ($node instanceof ResponseInterface) {
            $node = (string) $node->getBody();
        }

        parent::__construct($node, $currentUri, $baseHref);
    }
}
