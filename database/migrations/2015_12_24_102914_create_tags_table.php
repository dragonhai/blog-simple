<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->nullableTimestamps();
		});

		Schema::create('posts_tag', function(Blueprint $table){
			$table->integer('posts_id')->unsigned()->index();
			$table->foreign('posts_id')->references('id')->on('posts')->onDelete('cascade');

			$table->integer('tag_id')->unsigned()->index();
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
			
			$table->nullableTimestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts_tag');
		Schema::drop('tags');
	}

}
