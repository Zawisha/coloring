<?php

namespace App\Console\Commands;

use App\Models\Cat;
use App\Models\Colored;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a sitemap';

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
     * @return int
     */
    public function handle()
    {
        $sitemap = Sitemap::create();
        $sitemap->add(Url::create("/"));
        $sitemap->add(Url::create("/profile"));
        $sitemap->add(Url::create("/register"));
        $categoties_list= Cat::all();
        foreach ($categoties_list as $one_category) {
            $sitemap->add(Url::create("/cat/$one_category->slug"));
        }
        $colored_list= Colored::where('published', 1)->get();
        foreach ($colored_list as $one_coloring) {
            $sitemap->add(Url::create("/coloring/$one_coloring->slug"));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
