<?php

namespace App\Checkers;

use App\Analyser\Crawler;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

interface CheckerInterface
{
    /**
     * Extract info using checkers.
     *
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @param UriInterface $uri
     *
     * @return bool
     */
    public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri);

    /**
     * Get the critical level of the checker.
     *
     * @return string
     */
    public function level();

    /**
     * Get the message if the rule is passed.
     *
     * @return string
     */
    public function successMessage();

    /**
     * Get the message if the checker failed.
     *
     * @return string
     */
    public function failedMessage();
}
