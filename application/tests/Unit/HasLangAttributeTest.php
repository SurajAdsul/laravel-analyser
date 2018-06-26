<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Checkers\HasLangAttribute;

class HasLangAttributeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $args = $this->createArgumentsFromBlob('HtmlHasLangAttributePassed');
        $checker = new HasLangAttribute();
        $this->assertTrue($checker->check(...$args));
        $this->assertEquals('en', $checker->getLang());

        $args = $this->createArgumentsFromBlob('HtmlHasLangAttributeFailed');
        $checker = new HasLangAttribute();
        $this->assertFalse($checker->check(...$args));
    }
}
