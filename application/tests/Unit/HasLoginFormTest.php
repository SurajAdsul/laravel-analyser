<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Checkers\HasLoginForm;

class HasLoginFormTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $args = $this->createArgumentsFromBlob('LoginFormExistsPassed');
        $checker = new HasLoginForm();
        $this->assertTrue($checker->check(...$args));

        $args = $this->createArgumentsFromBlob('LoginFormExistsFailed');
        $checker = new HasLoginForm();
        $this->assertFalse($checker->check(...$args));
    }
}
