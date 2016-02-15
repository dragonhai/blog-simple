<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DependencyFailureTest extends TestCase
{
    public function testOne()
    {
        $this->assertFalse(FALSE);
    }

    /**
     * @depends testOne
     */
    public function testTwo()
    {
    }
}
