<?php

namespace App\Events;

use App\Models\SearchStat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KeywordsForScrappingFound
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param SearchStat[] $searchStats
     */
    public function __construct(public array $searchStats)
    {

    }

}
