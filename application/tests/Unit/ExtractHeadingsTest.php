<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Checkers\ExtractHeadings;

class ExtractHeadingsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $args = $this->createArgumentsFromBlob('HeadingExistsPassed');
        $checker = new ExtractHeadings();
        $this->assertTrue($checker->check(...$args));
        $this->assertEquals(2, $checker->getHeadingCount());

        $args = $this->createArgumentsFromBlob('HeadingExistsFailed');
        $checker = new ExtractHeadings();
        $this->assertFalse($checker->check(...$args));
        $this->assertEquals(0, $checker->getHeadingCount());
    }
}
