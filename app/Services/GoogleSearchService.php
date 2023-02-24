<?php

namespace App\Services;

use App\Contracts\SearchService;
use App\Events\KeywordsForScrappingFound;

class GoogleSearchService implements SearchService
{
    /**
     * @param array $seacrchStats
     * @return void
     */
    public function search(array $seacrchStats)
    {
        foreach (array_chunk($seacrchStats, 10) as $seacrchStatsChunk) {
            KeywordsForScrappingFound::dispatch($seacrchStatsChunk);
        }
    }

}
