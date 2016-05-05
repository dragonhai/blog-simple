<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/



Route::group(['middleware' => ['webAuth']], function(){

    // show new post form
    Route::get('new-post','PostController@create');
    
    // save new post
    Route::post('new-post','PostController@store');
    
    // edit post form
    Route::get('post/edit/{slug}', ['as' => 'post.edit', 'uses' => 'PostController@edit']);
    
    // update post
    Route::post('post/update', ['as' => 'post.update', 'uses' => 'PostController@update']);
    
    // delete post
    Route::get('delete/{id}','PostController@destroy');
    
    // display user's all posts
    Route::get('my-all-posts','UserController@user_posts_all');
    
    // display user's drafts
    Route::get('my-drafts','UserController@user_posts_draft');
    
    
    // add comment
    Route::post('comment/add',['as' => 'comment.add', 'uses' => 'CommentController@store']);
    
    // delete comment
    Route::post('comment/delete/{id}','CommentController@destroy'); 
});


Route::group(['middleware' => ['web']], function () {

    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);

    Route::get('/',['as' => 'home', 'uses' => 'PostController@index']);
    Route::get('/home',['as' => 'home.index', 'uses' => 'PostController@index']);
    Route::get('/posts',['as' => 'home.post', 'uses' => 'PostController@index']);

    //users profile
    Route::get('user/{id}',['as' => 'user.profile', 'uses' => 'UserController@profile'])->where('id', '[0-9]+');

    // display list of posts
    Route::get('user/{id}/posts',['as' => 'user.posts', 'uses' => 'UserController@user_posts'])->where('id', '[0-9]+');

    // Route::get('search/{query?}', ['as' => 'search', 'uses' => 'QueryController@search']);
    Route::get('search', function() {
        $search = Input::get('query');
        if (empty($search)) return Redirect::route('home');
        $query = str_slug($search);
      return Redirect::route('search', ['query' => $query])->with(['search' => $search]);
    });

    Route::get('search/{query}', ['as' => 'search', 'uses' => 'QueryController@search'])->where('query', '[A-Za-z0-9-_]+');

    // display single post
    Route::get('post/{slug}',['as' => 'post.show', 'uses' => 'PostController@show'])->where('slug', '[A-Za-z0-9-_]+');

    Route::get('fileentry', 'FileEntryController@index');
    Route::get('fileentry/get/{filename}', ['as' => 'getentry', 'uses' => 'FileEntryController@get']);
    Route::post('fileentry/store',['as' => 'addentry', 'uses' => 'FileEntryController@store']);
});

