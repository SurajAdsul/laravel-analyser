<?php

namespace Tests;

use App\Analyser\Crawler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createArgumentsFromBlob($name)
    {
        if (!ends_with($name, '.html')) {
            $name = "$name.html";
        }

        $response = new Response(200, [], file_get_contents(__DIR__ . "/Unit/Templates/$name"));
        $uri = new Uri('http://foo.bar/' . $name);
        $crawler = new Crawler($response, $uri);

        return [$crawler, $response, $uri];
    }
}
