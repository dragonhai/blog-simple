<?php

use App\Posts;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostsControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        Mockery::close();
    }
    /**
     * Go to page index success
     *
     * @return void
     */
    public function testIndexSuccess()
    {
        $this->call('GET', 'posts');

        // expect return status code 200
        // @resolveOther: $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseOk();

        // expect view has title and title = 'Lastest Posts'
        $this->assertViewHas('title', 'Latest Posts');

        // expect view has posts variable
        $this->assertViewHas('posts');
    }

    /**
     * Create posts
     */
    public function testStore()
    {   

        // $mock = $this->mock('App\User');
        // $mock->shouldReceive('canPost')->once();
        $this->call('GET', 'new-post');

        // $mock = $this->mock('App\Posts');
        // $mock->shouldReceive('save')->once();
        // $input = [
        //     'id' => 16,
        //     'title' => 'abc1', 
        //     'body' => 'body 1',
        //     'author_id' => 1,
        //     'active' => 1,
        //     // 'created_at' => '2016-01-11 10:13:22',
        //     // 'updated_at' => '2016-01-11 10:13:22',
        //     'published_at' => '2016-01-20 10:13:22',
        // ];
        // $this->post('new-post', $input);

        // $this->assertRedirectedToAction('PostController@edit');
        // $this->assertViewHas('posts');
        // $user = App\User::all()->first();
        // $this->be($user);
        // $this->assertTrue(Auth::check());

        // $input = [
        //     'title' => 'abc1', 
        //     'body' => 'body 1',
        //     'author_id' => '56',
        //     'published_at' => '01-01-2016'
        //     ];

    //             $mock = Mockery::mock('App\Http\Controllers\PostController');
    // $mock->expects($this->once())
    //      ->method('store');
//         ->will($this->returnValue('John Doe'));


        
        
        // $this->call('POST', 'store', $input);
        $this->assertResponseOk();

     
        // $this->assertRedirectedTo('home');
     
    }

    /**
     * Add post fail
     */
    public function testStoreFails()
    {
        // Input::replace($input = ['title' => '']);
 
        // $this->call('POST', 'posts');
 
        // $this->assertRedirectedToRoute('posts.create');
        // $this->assertSessionHasErrors();
    }
    
    /**
     * Add post success
     */
    public function testStoreSuccess()
    {
        // Input::replace($input = ['title' => 'Foo Title']);
 
        // Post::shouldReceive('create')->once();
 
        // $this->call('POST', 'posts');
 
        // $this->assertRedirectedToRoute('posts.index', ['flash']);
    }

    public function testCreate()
    {

        // Auth::shouldReceive('handle')->once()->andReturn(false);

        // $response = $this->call('GET', 'home');

        // Now we have several ways to go about this, choose the
        // one you're most comfortable with.

        // Check that you're redirecting to a specific controller action 
        // with a flash message
        // $this->assertRedirectedToAction(
        //      'AuthenticationController@login', 
        //      null, 
        //      ['flash_message']
        // );

        // Only check that you're redirecting to a specific URI
        // $this->assertRedirectedTo('login');

        // Just check that you don't get a 200 OK response.
        // $this->assertFalse($response->isOk());

        // Make sure you've been redirected.
        // $this->assertTrue($response->isRedirection());
    }
}
