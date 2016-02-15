<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewUserRegistration()
    {
        $this->visit('auth/register')
            ->type('bob', 'name')
            ->type('hello4@in.com', 'email')
            ->type('hello2', 'password')
            ->type('hello2', 'password_confirmation')
            ->press('Register')
            ->seePageIs('home');
    }

    public function testValidatorWhenRegistration()
    {

    }
}
