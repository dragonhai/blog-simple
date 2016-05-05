<?php

namespace App\Console\Commands;

use App\Posts;
use Illuminate\Console\Command;

class AutoCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:redis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache on redis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $posts = new Posts;
        $perpage = 5;
        //$posts->count() / $perpage
        for($i = $posts->count() / $perpage; $i > 100000; $i++) {
            $posts->cachePage($i);
        }
    }
}
