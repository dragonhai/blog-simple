<?php 
namespace App\Http\Controllers;

use App\Posts;
use App\Tag;
use App\User;
use Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;
use Carbon\Carbon;
use Redis;
use DB;
use Input;

use Illuminate\Http\Request;

// note: use true and false for active posts in postgresql database
// here '0' and '1' are used for active posts because of mysql database

class PostController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		DB::connection()->enableQueryLog();
		// $storage = Redis::Connection();

		// $popular = $storage->zRevRange('articleViews', 0, -1);

		// foreach ($popular as $value) {
		// 	$slug = str_replace('article:', '', $value);
		// 	echo "Article " . $slug . " is popular" . "</br>";
		// }
		$posts = new Posts;
		$currentPage = Input::get('page', '1');
		// $posts = Posts::latest($currentPage);
		$posts = $posts->cachePage($currentPage);
		

		$title = 'Latest Posts';


		return view('home',compact('posts', 'title'));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{

		if($request->user()->canPost())
		{
			$published_at = Carbon::now();
			$tag_list = Tag::lists('name');
			return view('posts.create', compact('published_at', 'tag_list'));
		}	
		else 
		{
			return redirect('/')->withErrors('You have not sufficient permissions for writing post');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PostFormRequest $request)
	{
		$post = new Posts();
		$post->title = $request->get('title');
		$post->body = $request->get('body');
		$post->published_at = $request->get('published_at');

		$post->slug = str_slug($post->title);
		$post->author_id = $request->user()->id;
		$tags = $request->input('tag_list');

		if($request->has('save'))
		{
			$post->active = 0;
			$message = 'Post saved successfully';

		}			
		else 
		{
			$post->active = 1;
			$message = 'Post published successfully';
		}
		$post->save();
		if($tags) $post->tags()->sync($tags);

		$post->createIndexElastic();

		return redirect(route('post.edit', [$post->slug]))->withMessage($message);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $slug)
	{

		// $storage = Redis::Connection();

		// if($storage->zScore('articleViews', 'article:'.$slug)) {
		// 	$storage->pipeline(function($pipe) use ($slug) {
		// 		$pipe->zIncrBy('articleViews', 1, 'article:' . $slug);
		// 		$pipe->incr('article:' . $slug . ':views');
		// 	});

		// } else {
		// 	$views = $storage->incr('article:' . $slug . ':views');
		// 	$storage->zIncrBy('articleViews', 1, 'article:' . $slug);
		// }

		// $views = $storage->get('article:' . $slug . ':views');

		// return 'This is an article with slug: ' . $slug . ' it has ' . $views . ' views';

		$post = Posts::where('slug', $slug)->first();
		if($post)
		{
			if(($post->active == false && $request->user() == null)
				|| ($request->user() 
					&& !$request->user()->isAdmin() 
					&& $post->active == false 
					&& $post->author_id !== $request->user()->id)){
				return redirect('/')->withErrors('requested page not found');
			}
			$comments = $post->comments;
		}
		else 
		{
			return redirect('/')->withErrors('requested page not found');
		}
		return view('posts.show', compact('post', 'comments'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $slug)
	{
		$post = Posts::where('slug',$slug)->first();
		$tags = Tag::lists('name', 'id');

		$tag_list = $post->tag_list->toArray();
		if($post && ($request->user()->id == $post->author_id || $request->user()->isAdmin()))
			return view('posts.edit', compact('post', 'tags', 'tag_list'));
		else 
		{
			return redirect('/')->withErrors('you have not sufficient permissions');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		//
		$post_id = $request->input('post_id');
		$post = Posts::find($post_id);

		if($post && ($post->author_id == $request->user()->id || $request->user()->isAdmin()))
		{
			$title = $request->input('title');
			$slug = str_slug($title);

			$duplicate = Posts::where('slug', $slug)->first();
			if($duplicate)
			{
				if($duplicate->id != $post_id)
				{
					return redirect('edit/'.$post->slug)->withErrors('Title already exists.')->withInput();
				}
				else 
				{
					$post->slug = $slug;
				}
			}
			
			$post->title = $title;
			$post->body = $request->input('body');
			$post->published_at = $request->get('published_at');
			
			// update tag list
			$tags = $request->input('tag_list');
			if($tags) $post->tags()->sync($tags);

			if($request->has('save'))
			{
				$post->active = 0;
				$message = 'Post saved successfully';
				$landing = route('post.edit', [$post->slug]);
			}			
			else {
				$post->active = 1;
				$message = 'Post updated successfully';
				$landing = route('post.show', [$post->slug]);
			}
			$post->save();

			$post->createIndexElastic();

			return redirect($landing)->withMessage($message);
		}
		else
		{
			return redirect('/')->withErrors('you have not sufficient permissions');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		//
		$post = Posts::find($id);
		if($post && ($post->author_id == $request->user()->id || $request->user()->isAdmin()))
		{
			$post->delete();
			$data['message'] = 'Post deleted Successfully';
		}
		else 
		{
			$data['errors'] = 'Invalid Operation. You have not sufficient permissions';
		}
		
		return redirect('/')->with($data);
	}
}
