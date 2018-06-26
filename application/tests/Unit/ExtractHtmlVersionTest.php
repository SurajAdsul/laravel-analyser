<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Checkers\ExtractHtmlVersion;

class ExtractHtmlVersionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $args = $this->createArgumentsFromBlob('HtmlVersionPassed');
        $checker = new ExtractHtmlVersion();
        $this->assertTrue($checker->check(...$args));
        $this->assertEquals('HTML5', $checker->getVersion());

        $args = $this->createArgumentsFromBlob('HtmlVersionFailed');
        $checker = new ExtractHtmlVersion();
        $this->assertFalse($checker->check(...$args));
    }
}
