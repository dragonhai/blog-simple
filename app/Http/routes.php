<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/',['as' => 'home', 'uses' => 'PostController@index']);

Route::get('/home',['as' => 'home', 'uses' => 'PostController@index']);

Route::get('/posts',['as' => 'home', 'uses' => 'PostController@index']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => ['auth']], function()
{
	// show new post form
	Route::get('new-post','PostController@create');
	
	// save new post
	Route::post('new-post','PostController@store');
	
	// edit post form
	Route::get('edit/{slug}','PostController@edit');
	
	// update post
	Route::post('update','PostController@update');
	
	// delete post
	Route::get('delete/{id}','PostController@destroy');
	
	// display user's all posts
	Route::get('my-all-posts','UserController@user_posts_all');
	
	// display user's drafts
	Route::get('my-drafts','UserController@user_posts_draft');
	
	
	// add comment
	Route::post('comment/add','CommentController@store');
	
	// delete comment
	Route::post('comment/delete/{id}','CommentController@distroy');
	
});

//users profile
Route::get('user/{id}','UserController@profile')->where('id', '[0-9]+');

// display list of posts
Route::get('user/{id}/posts','UserController@user_posts')->where('id', '[0-9]+');

// Route::get('search/{query?}', ['as' => 'search', 'uses' => 'QueryController@search']);
Route::get('search', function() {
	$search = Input::get('query');
	// dd(empty($search));
	if (empty($search)) return Redirect::route('home');
	$query = str_slug($search);
  return Redirect::route('search', ['query' => $query])->with(['search' => $search]);
});

Route::get('search/{query}', ['middleware'=> 'test', 'as' => 'search', 'uses' => 'QueryController@search'])->where('query', '[A-Za-z0-9-_]+');

// display single post
Route::get('/{slug}',['as' => 'post', 'uses' => 'PostController@show'])->where('slug', '[A-Za-z0-9-_]+');


