<?php

namespace App\Checkers;

use App\Analyser\Crawler;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

class ExtractTitle extends AbstractChecker
{
    private $title;

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @param UriInterface $uri
     * @return bool
     */
    public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri): bool
    {
        if (count($tags = $crawler->filter('title'))) {
            $this->title = trim($tags->first()->text());
        }

        return (bool) $this->title;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return "Title of this page is `{$this->title}`.";
    }

    /**
     * @return string
     */
    public function failedMessage(): string
    {
        return 'Unable to extract title from this page';
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
