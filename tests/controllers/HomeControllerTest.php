<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
    /**
     * Check home success
     */
    public function testIndexSuccess()
    {
        $response = $this->call('GET', 'home');
        $this->assertResponseOk();
    }

}
