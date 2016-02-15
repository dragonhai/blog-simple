<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserViewTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * User regiter success
     *
     * @return void
     */
    public function testUserRegisterSuccess()
    {
        $this->visit('auth/register')
            ->type('bob', 'name')
            ->type('hello7@in.com', 'email')
            ->type('hello2', 'password')
            ->type('hello2', 'password_confirmation')
            ->press('Register')
            ->seePageIs('home');
    }
    public function testUserRegisterNotSuccess()
    {
        $this->visit('auth/register')
            ->type('bob1', 'name')
            ->type('hello7@in.com', 'email')
            ->type('hello2', 'password')
            ->type('hello2', 'password_confirmation')
            ->press('Register')
            ->seePageIs('/');
    }

    /**
     * Test: View Login
     *
     * Description:
     * This will test that the user will be able to view the login page.
     *
     * @return void
     */
    public function testViewLogin()
    {
        $this->visit('/auth/login')
             ->see('Login');
    }

    /**
     * Test: Email Validation
     *
     * Description:
     * This will test that the user has entered a valid email into the email field.
     *
     * @return void
     */
    public function testEmailValidation()
    {
        $this->visit('/auth/login')
             ->submitForm('Login', ['email' => 'notemail', 'password' => 'test'])
             ->see('The email must be a valid email address.');
    }


    /**
     * Test: Required Fields
     *
     * Description:
     * This will test that the required fields of the login form have been filled out.
     *
     * @return void
     */
    public function testRequiredFields()
    {
        $this->visit('/auth/login')
             ->submitForm('Login')
             ->see('The email field is required.')
             ->see('The password field is required.');
    }

    /**
     * Test: Successful Auth
     *
     * Description:
     * This will test that a user who is registered and activated in the database has successfully logged in
     * and is taken to the Dashboard page.
     *
     * @return void
     */
    public function testSuccessAuth()
    {
        Sentinel::registerAndActivate([
            'email'    => 'admin@change.me',
            'password' => 'test',
        ]);
        $this->visit('/auth/login')
             ->submitForm('Login', ['email' => 'admin@change.me', 'password' => 'test'])
             ->see('Dashboard');
    }

    /**
     * Test: Failed Auth
     *
     * Description:
     * This will test that a user who is registered and activated in the database has failed to log in
     * and is taken back to the login page and sees that error message.
     *
     * @return void
     */
    public function testFailAuth()
    {
        Sentinel::registerAndActivate([
            'email'    => 'admin@change.me',
            'password' => 'test',
        ]);
        $this->visit('/auth/login')
             ->submitForm('Login', ['email' => 'admin@change.me', 'password' => 'test1'])
             ->see('Email \ Password combination incorrect.');
    }

    /**
     * Test: Logout
     *
     * Description:
     * This will test that a user who is registered and activated in the database has successfully logged in
     * and has now chosen to logout. This will log the user out and redirect them to the login page.
     *
     * @return void
     */
    public function testLogout()
    {
        $user = Sentinel::registerAndActivate([
            'email'    => 'admin@change.me',
            'password' => 'test',
        ]);
        Sentinel::login($user);
        $this->visit('/auth/logout')
             ->see('Login');
    }

    public function testChangePassword()
    {
        $this->visit('/login')
            ->click('Forgot Your Password?')
            ->seePageIs('/forgot-password')
            ->type('test@test.com','email')
            ->press('Send Password Reset Link')
            ->seePageIs('/login')
            ->see('A password reset link was sent to the email address supplied.')
            ->seeInDatabase('password_resets', ['email' => 'test@test.com']);

        $uuid = DB::table('password_resets')
            ->where('email', '=', 'test@test.com')
            ->value('token');

        $this->visit('/reset-password/' . $uuid)
            ->type('bbbbbbbb','password')
            ->type('bbbbbbbb','password_confirmation')
            ->press('Change Password')
            ->see('Your password was reset.')
            ->seePageIs('/login')
            ->type('test@test.com','email')
            ->type('bbbbbbbb','password')
            ->press('Sign In')
            ->seePageIs('/welcome')
            ->click('Logout')
            ->seePageIs('/login');

        // Change password back
        $this->visit('/login')
            ->click('Forgot Your Password?')
            ->seePageIs('/forgot-password')
            ->type('test@test.com','email')
            ->press('Send Password Reset Link')
            ->seePageIs('/login')
            ->see('A password reset link was sent to the email address supplied.')
            ->seeInDatabase('password_resets', ['email' => 'test@test.com']);

        $uuid = DB::table('password_resets')
            ->where('email', '=', 'test@test.com')
            ->value('token');

        $this->visit('/reset-password/' . $uuid)
            ->type('abcd1234','password')
            ->type('abcd1234','password_confirmation')
            ->press('Change Password')
            ->see('Your password was reset.')
            ->seePageIs('/login')
            ->type('test@test.com','email')
            ->type('abcd1234','password')
            ->press('Sign In')
            ->seePageIs('/welcome')
            ->click('Logout')
            ->seePageIs('/login');
    }
}
