<?php

namespace App\Providers;

use App\Contracts\FileInputParser;
use App\Contracts\Searcher;
use App\Parsers\CsvParser;
use App\Searchers\GoogleSearcher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileInputParser::class, CsvParser::class);
        $this->app->bind(Searcher::class, GoogleSearcher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
