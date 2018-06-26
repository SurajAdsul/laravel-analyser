<?php

namespace App\Checkers;

use App\Analyser\Crawler;
use App\Facades\Markdown;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractChecker implements CheckerInterface
{
    abstract public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri);

    public function level(): string
    {
        return Levels::WARNING;
    }

    public function successMessage(): string
    {
        throw new \Exception('Method not implemented');
    }

    public function failedMessage(): string
    {
        throw new \Exception('Method not implemented');
    }

    public function __get($name): string
    {
        if (in_array($name, ['successMessage', 'failedMessage'], true)) {
            return Markdown::parse($this->$name());
        }

        return $this->$name;
    }
}
