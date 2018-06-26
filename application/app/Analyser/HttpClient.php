<?php

namespace App\Analyser;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class HttpClient
{
    /**
     * @var
     */
    private $client;
    private $statusCode;

    /**
     * HttpClient constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->setClient($client ?: new Client());
    }

    /**
     * @param Client $client
     */
    private function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $url
     * @return $response
     */
    public function fetchData($url)
    {
        $response = $this->client->get($url, ['exceptions' => false]);

        $this->statusCode = $response->getStatusCode();

        return $response;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= Response::HTTP_OK && $this->statusCode < Response::HTTP_MULTIPLE_CHOICES;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string
    {
        return $this->statusCode.': '.Response::$statusTexts[$this->statusCode];
    }
}
