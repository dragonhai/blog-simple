<?php namespace App;

use Carbon\Carbon;
use App\BaseModel;
use Elasticsearch;
use Cache;
use DB;
use Input;
use Illuminate\Pagination\LengthAwarePaginator;

class Posts extends BaseModel {

	//posts table in database

	protected $guarded = [];
	public function comments()
	{
		return $this->hasMany('App\Comments','on_post');
	}
	
	public function author()
	{
		return $this->belongsTo('App\User','author_id');
	}

	public function tags() {
		return $this->belongsToMany('App\Tag')->withTimestamps();
	}

	public static function latest($currentPage = 1, $column = 'created_at'){
		$query = self::where('active','1');
		$perPage = 5;
		$cacheId = 'blog_posts_cache:' . $currentPage;

		$result = Cache::remember($cacheId, 30, function() use ($query, $column, $perPage) {
			$paginator = $query->orderBy($column,'desc')->paginate($perPage);
			return $paginator;
		});

		return $result;
	}

	public function cachePage($currentPage){
		$perPage = 5;
		$page = $currentPage - 1;
		$offset = $page * $perPage;
		$cacheId = 'blog_posts_cache:' . $currentPage;

		$result = Cache::remember($cacheId, 5 * 12 * 30 * 24 * 60, function() use ($page, $perPage, $offset, $currentPage) {
			$total = $this->count();
			$pagedData = DB::select(DB::raw(' select * from `posts` where `active` = 1 order by `created_at` desc limit ' . $perPage . ' offset ' . $offset));

			$paginator= new LengthAwarePaginator($pagedData, $total, $perPage, $currentPage);
			$paginator->setPath('http://localhost:9300');
			
			foreach ($paginator as $index => $item) {
				$user = User::find($item->author_id);
				$paginator[$index]->author = (object)["name" => $user->name]; 
			}
			return $paginator;
		});
		return $result;
	}
	
	public static function oldest($column = 'created_at'){
		return self::where('active','1')->orderBy($column,'asc');
	}

	public function setPublishedAtAttribute($date)
	{
		$this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d', $date);
	}

	public function getTagListAttribute() {
		return $this->tags->lists('id');
	}

	public function createIndexElastic($index = 'blog', $type = 'post') 
	{
		 // Elaticsearch index
		$published_at = Carbon::parse($this->published_at)->format('Y-m-d');
		$data = [
			'body' => [
				'title' => $this->title,
				'body' => $this->body,
				'author_id' => $this->author_id,
				'slug' => $this->slug,
				'published_at' => $published_at,
				'author' => ['name' => $this->author->name],
				'active' => $this->active
			],
			'index' => $index,
			'type' => $type,
			'id' => $this->id,
		];

		$return = Elasticsearch::index($data);
	}

	public static function searchElastic($slug, $per_page = 1, $from = 0, $index = 'blog', $type = 'post') {
		$params = [
			'index' => $index,
			'type' => $type,
			'body' => [
				'size' => $per_page,
				'from' => $from,
				'query' => [
					'bool' => [
						'should' => [
							['match' => ['title' => $slug]],
							['match' => ['body' => $slug]]
						]
					]
				]
			]
		];

		$result = Elasticsearch::search($params);

		// reIndex for old data not exists index on elasticsearch
		$posts = $result['hits']['hits'];

		$reIndex = false;
		foreach ($posts as $post) {
			if(!isset($post->title, $post->slug, $post->author_id, $post->published_at, $post->author, $post->body, $post->active)) {
				$reIndex = true;
				$id = $post['_id'];
				$item = self::find($id);
				$item->createIndexElastic();
			}
		}

		if($reIndex) {
			$result = Elasticsearch::search($params);
		}
		return $result['hits'];
	}
}
