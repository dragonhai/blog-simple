<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Posts;
use DB;

use Illuminate\Http\Request;

class UserController extends Controller {

	/*
	 * Display the posts of a particular user
	 * 
	 * @param int $id
	 * @return Response
	 */
	public function user_posts($id)
	{
		//
		$posts = Posts::where('author_id',$id)->where('active','1')->orderBy('created_at','desc')->paginate(5);
		$user = User::find($id);
		// dd($user);
		if($user) {
			$title = $user->name;
			return view('home')->withPosts($posts)->withTitle($title);	
		} else {
			return redirect('/')->withErrors('Not found user');
		}
	}

	public function user_posts_all(Request $request)
	{
		//
		$user = $request->user();
		$posts = Posts::where('author_id',$user->id)->orderBy('created_at','desc')->paginate(5);
		$title = $user->name;
		return view('home')->withPosts($posts)->withTitle($title);
	}
	
	public function user_posts_draft(Request $request)
	{
		//
		$user = $request->user();
		$posts = Posts::where('author_id',$user->id)->where('active','0')->orderBy('created_at','desc')->paginate(5);
		$title = $user->name;
		return view('home')->withPosts($posts)->withTitle($title);
	}

	/**
	 * profile for user
	 */
	public function profile(Request $request, $id) 
	{
		// DB::connection()->enableQueryLog();

		$data['user'] = User::find($id);
		if (!$data['user'])
			return redirect('/')->withErrors('Not found user');

		if ($request -> user() && $data['user']->id == $request->user()->id) {
			$data['author'] = true;
		} else {
			$data['author'] = null;
		}

		$posts = $data['user']->posts()->where('active', '1');

		$data['comments_count'] = $data['user']->comments()->count();
		$data['posts_count'] = $data['user']->posts()->count();

		// the query
$sql = "select count(*) as total from posts use index(primary) where author_id = ? and author_id is not null and active = ?";
$found_total = DB::select($sql, [$id, 1]);
$data['posts_active_count'] = $found_total[0]->total;
// var_dump($data['posts_active_count']);
$balance = "";
if($data['posts_active_count'] > 50000){
	$balance = "use index(primary)";
} 

$sql1 = "select * from `posts` " . $balance . "  where `posts`.`author_id` = ? and `posts`.`author_id` is not null and `active` = ? order by `created_at` desc limit 5";
$latest_posts = collect(DB::select($sql1, [$id, 1]));

// dd(collect($rows1));


		// $data['posts_active_count'] = $found_total[0]->total;
		$data['posts_draft_count'] = $data['posts_count'] - $data['posts_active_count'];
		$data['latest_posts'] = $latest_posts; //$posts->orderBy('created_at','desc')->take(5)->get();
		// dd($data['latest_posts']);
		$data['latest_comments'] = $data['user']->comments()->orderBy('created_at','desc')->take(5)->get();

		// $log = DB::getQueryLog();
		// print_r($log);
		// dd(111);

		return view('admin.profile', $data);
	}

}

