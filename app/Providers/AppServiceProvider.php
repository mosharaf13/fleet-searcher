<?php

namespace App\Providers;

use App\Contracts\FileInputParser;
use App\Contracts\Searcher;
use App\Contracts\SearchService;
use App\Contracts\Storage;
use App\Parsers\CsvParser;
use App\Searchers\GoogleSearcher;
use App\Services\GoogleSearchService;
use App\Storages\DbStorage;
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
        $this->app->bind(Storage::class, DbStorage::class);
        $this->app->bind(SearchService::class, GoogleSearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
