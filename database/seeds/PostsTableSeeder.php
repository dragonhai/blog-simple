<?php
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder {

   public function run()
   {
        factory(App\Posts::class, 500000)->create()->each(function($posts) {
            var_dump($posts->save());
            $posts->createIndexElastic();
        });
   }

}
