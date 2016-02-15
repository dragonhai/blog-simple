<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @coversDefaultClass User
 */
class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected $userWithoutData;
    protected $userValid;

    public function setUp()
    {
        parent::setUp();

        factory(App\User::class)->create();

    }

    /**
     * @before
     */
    public function setUpUserWithoutData()
    {
        // set up user with out data
        $this->userWithoutData = $user = new User;

        $user->setAutoValidate(true);
        $user->setVisible($user->getFillable());

        $user->save();
    }

    /**
     * Create new user with out data expect fail
     * @group userWithoutData
     */
    public function testCreateNewUserWithoutDataFail()
    {
        $this->assertTrue($this->userWithoutData->errors()->any());
    }

    /**
     * Name has error
     * @group nameUser
     * @group userWithoutData
     */
    public function testNameHasError()
    {
        $this->assertTrue($this->userWithoutData->errors()->has('name'));
    }

    /**
     * Name is required
     * @group nameUser
     * @group userWithoutData
     * @depends testNameHasError
     */
    public function testNameIsRequired()
    {
        $errors = $this->userWithoutData->errors()->jsonSerialize();
        $this->assertContains('The name field is required.', $errors['name']);
    }

    /**
     * Email has error
     * @group emailUser
     * @group userWithoutData
     */
    public function testEmailHasError()
    {
        $errors = $this->userWithoutData->errors();
        $this->assertTrue($errors->has('email'));        
    }

    /**
     * Email is required
     * @group emailUser
     * @group userWithoutData
     * @depends testEmailHasError
     */
    public function testEmailIsRequired()
    {
        $errors = $this->userWithoutData->errors()->jsonSerialize();
        $this->assertContains('The email field is required.', $errors['email']);
    }

    /**
     * Email invalid
     * @group emailUser
     * @group userWithoutData
     * @depends testEmailHasError
     */
    public function testEmailInvalid()
    {
        $this->userWithoutData->email = "test";
        $this->userWithoutData->save();

        $errors = $this->userWithoutData->errors()->jsonSerialize();
        $this->assertContains('The email must be a valid email address.', $errors['email']);
    }

    /**
     * Password has error
     * @group passwordUser
     * @group userWithoutData
     */
    public function testPasswordHasError()
    {
        $this->assertTrue($this->userWithoutData->errors()->has('password'));
    }

    /**
     * Password is required
     * @group passwordUser
     * @group userWithoutData
     * @depends testPasswordHasError 
     */
    public function testPasswordIsRequired()
    {
        $errors = $this->userWithoutData->errors()->jsonSerialize();
        $this->assertContains('The password field is required.', $errors['password']);
    }

    /**
     * @before
     */
    public function setUpUserValidEMail()
    {

        // set up user valid
        $this->userValid = $user = new User;

        $user->setAutoValidate(true);
        $user->setVisible($user->getFillable());

        $user->email = "test@1pac.jp";
        $user->save();
    }

    /**
     * Email has not error
     * @group emailError
     * @group userValid
     */
    public function testEmailHasNotError() 
    {
        $this->assertFalse($this->userValid->errors()->has('email'));
    }

    /**
     * User is admin
     * @group userRole
     */
    public function testUserIsAdmin()
    {
        $user = User::where('role', 'admin')->first();
        var_dump(User::all()->toArray());
        $this->assertTrue($user->isAdmin());
    }
}
