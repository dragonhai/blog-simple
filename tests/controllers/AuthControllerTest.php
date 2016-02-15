<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Session::start();
        // $this->authController = new App\AuthController
    }

    public function testLoginInvalidInput()
    {
        $this->call('POST', 'auth/login', [
            '_token' => csrf_token(),
        ]);

        $this->assertHasOldInput();
        $this->assertSessionHasErrors();
        $this->assertResponseStatus(302);
    }
    
    public function testLoginSuccess()
    {
        // Session::start();

        // Mock Auth Guard Object
        $guardMock = Mockery::mock('Illuminate\Auth\Guard');
        $this->app->instance('Illuminate\Contracts\Auth\Guard', $guardMock);

        /* @see App\Http\Middleware\RedirectIfAuthenticated */
        $guardMock
            ->shouldReceive('check')
            ->once()
            ->andReturn(false);

        $this->call('POST', 'auth/login', [
            'email'    => 'b.thanhhai@gmail.com',
            'password' => '123456',
            '_token'   => csrf_token(),
        ]);

        $this->assertRedirectedTo('home');
    }

    public function testLoginFailed()
    {
        // Mock Auth Guard Object
        $guardMock = Mockery::mock('Illuminate\Auth\Guard');
        $this->app->instance('Illuminate\Contracts\Auth\Guard', $guardMock);

        /* @see App\Http\Middleware\RedirectIfAuthenticated */
        $guardMock
            ->shouldReceive('check')
            ->once()
            ->andReturn(false);
            
        $this->call('POST', 'auth/login', [
            'email'    => 'jaceju@gmail.com',
            'password' => 'password',
            '_token'   => csrf_token(),
        ]);

        $this->assertHasOldInput();
        $this->assertSessionHasErrors();
        $this->assertRedirectedTo('auth/login');
    }
    // use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
    // use Illuminate\Foundation\Auth\AuthenticatesUsers;
    public function testLogout()
    {
        $user = new App\User(['email' => 'b.thanhhai@gmail.com']);
        $this->be($user);

        // Mock Auth Guard Object
        $guardMock = Mockery::mock('App\Http\Controllers\Auth\AuthController');
        // $this->app->instance('App\Http\Controllers\Auth\AuthController', $guardMock);

        /* @see App\Http\Middleware\RedirectIfAuthenticated */
        // $authMock = Mockery::mock('Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers', 'Illuminate\Foundation\Auth\AuthenticatesUsers');

        // $guardMock = $this->getMockForTrait('Illuminate\Foundation\Auth\AuthenticatesUsers');

        // $guardMock->expects($this->any())
        //      ->method('getLogout')
        //      ->will($this->returnValue(TRUE));

        // $mock->getLogout();
        $guardMock
            ->shouldReceive('getLogout')
            ->once();
        // Event::shouldReceive('handle')
        //     ->once()
        //     ->with('auth.logout', [$user]);
        $this->call('GET', 'auth/logout');

        // $this->assertRedirectedTo('/');
    }
}
