<?php namespace App\Http\Controllers;

use App\Posts;
use App\Http\Controllers\Controller;

use App\Http\Requests\SearchFormRequest;
use Illuminate\Http\Request;

class QueryController extends Controller {

	public function search(Request $request, $query)
	{
		// Gets the query string from our form submission 
		$slug = str_slug($query);
		$posts = Posts::where('slug', 'LIKE', '%' . $slug . '%')->paginate(2);

		$query = $request->session()->get('search');
		$request->session()->keep(['search']);

		// returns a view and passes the view the list of articles and the original query.
		return view('page.search', compact('posts', 'query'));
	}

}
