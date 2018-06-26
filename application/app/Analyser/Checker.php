<?php

namespace App\Analyser;

use App\Checkers\Levels;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Container\Container;

class Checker
{
    private $container;
    private $client;

    public function __construct(Container $container, HttpClient $client)
    {
        $this->container = $container;
        $this->client = $client;
    }

    /**
     * Extract info using different checkers.
     *
     * @param $url
     * @return \Generator
     */
    public function check($url)
    {
        $url = new Uri($url);

        $response = $this->client->fetchData($url);

        if (! $this->client->isSuccessful()) {
            return yield [
                'passed' => false,
                'message' => $this->client->getStatusMessage(),
            ];
        }

        $crawler = new Crawler($response, $url);

        foreach (config('analysers') as $analyserClassName) {
            $checker = $this->container->make($analyserClassName);

            try {
                $result = $checker->check($crawler, $response, $url);
                yield [
                    'passed' => $result,
                    'message' => $result ? $checker->successMessage : $checker->failedMessage,
                    'level' => $checker->level(),
                ];
            } catch (\Exception $e) {
                yield [
                    'passed' => false,
                    'message' => "Error checking rule `{$analyserClassName}`.",
                    'level' => Levels::ERROR,
                ];
            }
        }
    }
}
