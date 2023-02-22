<?php

namespace App\Listeners;

use App\Events\SearchStatGenerated;
use App\Models\SearchStat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreSearchStatOnDb
{

    /**
     * Handle the event.
     */
    public function handle(SearchStatGenerated $event): void
    {
        SearchStat::create([
            'keyword' => $event->keyword,
            'ads_count' => $event->adsCount,
            'links_count' => $event->linksCount,
            'total_result_count' => $event->searchCount,
            'raw_response' => $event->rawResponse,
        ]);
    }
}
