<?php

namespace App\Checkers;

use App\Analyser\Crawler;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

class ExtractHeadings extends AbstractChecker
{
    private $headings;
    private $headingCount;

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @param UriInterface $uri
     * @return bool
     */
    public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri): bool
    {
        $data = [];
        for ($h = 1; $h <= 6; $h++) {
            $data['h'.$h] = $crawler->filter('h'.$h)->each(function (Crawler $node, $i) {
                return $node->text();
            });
        }

        $this->setHeadingsCount($data);

        $this->headings = $this->extractHeadings($data);

        return $this->headings;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return "`{$this->headingCount}` Headings found within this webpage".$this->headings;
    }

    /**
     * @return string
     */
    public function failedMessage(): string
    {
        return 'Unable to extract title from this page';
    }

    /**
     * Extract Headings.
     *
     * @param $data
     * @return string
     */
    private function extractHeadings($data)
    {
        return collect($data)->filter()->map(function ($item, $key) {
            $data = collect($item)->implode('<br>');

            return '<br>`<'.$key.'>`<br>'.$data;
        })->implode('');
    }

    /**
     * Set heading count.
     *
     * @param $data
     */
    private function setHeadingsCount($data)
    {
        $this->headingCount = collect($data)->map(function ($item) {
            return collect($item)->count();
        })->sum();
    }

    /**
     * @return int
     */
    public function getHeadingCount(): int
    {
        return $this->headingCount;
    }
}
