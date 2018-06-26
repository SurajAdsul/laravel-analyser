<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Checkers\ExtractTitle;

class ExtractTitleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $args = $this->createArgumentsFromBlob('TitleExistsPassed');
        $checker = new ExtractTitle();
        $this->assertTrue($checker->check(...$args));
        $this->assertEquals('Laravel', $checker->getTitle());

        $args = $this->createArgumentsFromBlob('TitleExistsFailed');
        $checker = new ExtractTitle();
        $this->assertFalse($checker->check(...$args));
    }
}
