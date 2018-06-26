<?php

namespace Tests\Unit;

use App\Checkers\ExtractLinks;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ExtractLinksTest extends TestCase
{
    public function testCheck()
    {
        $mock = new MockHandler([
            new Response(200),
            new Response(200),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $args = $this->createArgumentsFromBlob('LinkCountPassed');
        $rule = new ExtractLinks($client);
        $this->assertTrue($rule->check(...$args));
    }

    public function testCheckFails()
    {
        $mock = new MockHandler([
            new Response(200),
            new Response(404),
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $args = $this->createArgumentsFromBlob('LinkCountPassed');
        $rule = new ExtractLinks($client);
        static::assertFalse($rule->check(...$args));
    }

    public function testCorrectLinks()
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([
            new Response(200),
            new Response(404),
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        // Add the history middleware to the handler stack.
        $handler->push($history);

        $client = new Client(['handler' => $handler]);

        $args = $this->createArgumentsFromBlob('LinkCountPassed');
        $rule = new ExtractLinks($client);
        $rule->check(...$args);

        // Count the number of transactions
        $this->assertCount(3, $container);

        /** @var Request $request */
        $request = $container[0]['request'];
        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals('http://google.com', (string)$request->getUri());

        /** @var Request $request */
        $request = $container[1]['request'];
        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals('http://analyser.test', (string)$request->getUri());
    }
}
