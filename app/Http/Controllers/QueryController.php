<?php namespace App\Http\Controllers;

use App\Posts;
use App\Http\Controllers\Controller;

use App\Http\Requests\SearchFormRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class QueryController extends Controller {

	public function searchQuery(Request $request, $query) {
		// Gets the query string from our form submission 
		$slug = str_slug($query);
		$posts = Posts::where('slug', 'LIKE', '%' . $slug . '%')->paginate(2);

		$query = $request->session()->get('search');
		$request->session()->keep(['search']);

		// returns a view and passes the view the list of articles and the original query.
		return view('page.search', compact('posts', 'query'));
	}

	public function searchElastic(Request $request, $query) {
		$slug = $query;
		// dd($slug);
		$per_page = $request->get('limit', 5);
	    $from = ($request->get('page', 1) - 1) * $per_page;
	    $access = Posts::searchElastic($slug, $per_page, $from);

	    $posts = new LengthAwarePaginator(
	            $access['hits'],
	            $access['total'],
	            $per_page,
	            Paginator::resolveCurrentPage(),
	            ['path' => Paginator::resolveCurrentPath()]);

		$query = $request->session()->get('search');
		$request->session()->keep(['search']);
		
		return view('page.elasticsearch', compact('posts', 'query'));
	}

	public function search(Request $request, $query)
	{
		return $this->searchElastic($request, $query);
		// return $this->searchQuery($request, $query);
	}
}
