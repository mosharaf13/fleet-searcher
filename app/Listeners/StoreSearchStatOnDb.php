<?php

namespace App\Listeners;

use App\Events\SearchStatGenerated;
use App\Models\SearchStat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreSearchStatOnDb
{

    /**
     * Handle the event.
     */
    public function handle(SearchStatGenerated $event): void
    {
        $event->keyword->ads_count = $event->adsCount;
        $event->keyword->links_count = $event->linksCount;
        $event->keyword->total_result_count = $event->searchCount;
        $event->keyword->raw_response = $event->rawResponse;
        $event->keyword->scrap_status = SearchStat::SCRAP_STATUS_COMPLETED;
        $event->keyword->save();
    }
}
