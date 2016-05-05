<?php

use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $password = '123456';
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt($password), //bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Posts::class, function (Faker\Generator $faker) {
    $title = "[" . Carbon::now()->timestamp . "] " . $faker->unique()->sentence(5);
    $slug = str_slug($title);
    $published_at = Carbon::now()->format('Y-m-d');

    return [
        'author_id' => 3,
        'title' => $title,
        'body' => $faker->paragraph(4),
        'slug' => $slug,
        'active' => 1,
        'published_at' => $published_at,
    ];
});
