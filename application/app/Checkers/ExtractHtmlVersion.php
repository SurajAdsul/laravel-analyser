<?php

namespace App\Checkers;

use App\Analyser\Crawler;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

class ExtractHtmlVersion extends AbstractChecker
{
    const VALID_DOCTYPE = 'HTML5';

    private $version;

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @param UriInterface $uri
     * @return bool
     */
    public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri): bool
    {
        if (preg_match('/<!doctype.*?\/\/dtd\s+([^\/]*)\/\/EN.*?dtd">/i', trim($response->getBody()), $matches)) {
            $this->version = trim($matches[1]);
        } elseif (preg_match('/^<!doctype\s.*?>/i', trim($response->getBody()), $matches)) {
            $this->version = self::VALID_DOCTYPE;
        }

        return (bool) $this->version;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return "Html version of this webpage is `{$this->version}`.";
    }

    /**
     * @return string
     */
    public function failedMessage(): string
    {
        return 'Unable to detect HTML version of this page';
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
