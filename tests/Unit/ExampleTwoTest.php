<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTwoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        dd('example 2');
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testy()
    {
        dd(__FUNCTION__);
        $this->assertTrue(true);
    }
}
