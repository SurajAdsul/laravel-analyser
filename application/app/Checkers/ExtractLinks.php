<?php

namespace App\Checkers;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use App\Analyser\Crawler;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Collection;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class ExtractLinks extends AbstractChecker
{
    private $message;
    private $fail;
    private $requests;
    protected $client;
    private $externalLinkCount = 0;
    private $internalLinkCount = 0;

    /**
     * Check for broken links on the page.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @param UriInterface $uri
     * @return bool
     */
    public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri): bool
    {
        $ok = 0;

        $this->ValidateUrls($crawler);

        if (empty($this->requests)) {
            $this->message = 'No links found.';
            return true;
        }

        $linkTypes = collect($this->requests)->keys()->flip()->map(function ($item, $key) use ($uri) {
            return $this->isExternal($key, $uri->getHost());
        });

        $this->setLinkCount($linkTypes);

        $pool = new Pool($this->client, $this->requests, [
            'concurrency' => 5,
            'fulfilled' => function () use (&$ok) {
                $ok++;
            },
            'rejected' => function (RequestException $e) use (&$ok, &$fail) {
                if ($e->getCode() !== 403) {
                    // Retry the request as HEAD, as not every host supports HEAD
                    $retryRequest = $e->getRequest()->withMethod('GET');

                    try {
                        $this->client->send($retryRequest);
                        $ok++;

                        return;
                    } catch (RequestException $retryException) {
                        // Failed again
                    }
                }

                if ($response = $e->getResponse()) {
                    $result = "* `{$response->getStatusCode()} {$response->getReasonPhrase()}` - ";
                } else {
                    $result = '* `UNKNOWN ERROR` - ';
                }

                $result .= $e->getRequest()->getUri();
                $this->fail[] = $result;
            },
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();

        $this->message = $this->fail ?
            'Found **' . count($this->fail) . '** broken ' . str_plural('link', count($this->fail)) . ':' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $this->fail) :
            "All $ok " . str_plural('link', $ok) . ' on the page are working.';
        $this->message .= '<br> There are `' . $this->internalLinkCount . '` internal and `' . $this->externalLinkCount . '` external links on this page.';

        return !$this->fail;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function failedMessage(): string
    {
        return $this->message;
    }

    /**
     * @param Crawler $crawler
     */
    private function ValidateUrls(Crawler $crawler)
    {

        foreach ($crawler->filter('a')->links() as $link) {
            $uri = $link->getUri();

            //Validate URLs
            if (filter_var($uri, FILTER_VALIDATE_URL) === false) {
                $this->fail[] = '* `Bad URL format` - ' . $uri;
            }

            // Strip fragment(#tab1 etc..)
            $request = new Request('HEAD', $uri);
            $uri = $request->getUri()->withFragment('');
            $request = $request->withUri($uri);

            if (in_array($uri->getScheme(), ['http', 'https'], true)) {
                $this->requests[(string)$request->getUri()] = $request;
            }
        }
    }

    /**
     * @param $url
     * @param $hostUrl
     * @return bool
     */
    private function isExternal($url, $hostUrl): bool
    {
        $components = parse_url($url);
        if (empty($components['host'])) return false;  // we will treat url like '/relative.php' as relative
        if (strcasecmp($components['host'], $hostUrl) === 0) return false; // url host looks exactly like the local host
        return strrpos(strtolower($components['host']), '.' . $hostUrl) !== strlen($components['host']) - strlen('.' . $hostUrl); // check if the url host is a subdomain
    }

    /**
     * @param Collection $linkTypes
     */
    private function setLinkCount(Collection $linkTypes)
    {

        $this->externalLinkCount = $linkTypes->filter()->count();
        $this->internalLinkCount = $linkTypes->filter(function ($value, $key) {
            return !$value;
        })->count();

    }

}
