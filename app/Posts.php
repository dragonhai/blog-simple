<?php namespace App;

use Carbon\Carbon;
use App\BaseModel;

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

	public static function latest($column = 'created_at'){
		return self::where('active','1')->orderBy($column,'desc');
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
}
