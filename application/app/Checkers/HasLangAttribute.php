<?php

namespace App\Checkers;

use App\Analyser\Crawler;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

class HasLangAttribute extends AbstractChecker
{
    private $lang;

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @param UriInterface $uri
     * @return bool
     */
    public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri): bool
    {
        $this->lang = trim($crawler->attr('lang'));

        return (bool) $this->lang;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return "`<html>` tag has a `lang` attribute (`$this->lang`).";
    }

    /**
     * @return string
     */
    public function failedMessage(): string
    {
        return '`<html>` tag should have an explicit `lang` attribute.';
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
}
