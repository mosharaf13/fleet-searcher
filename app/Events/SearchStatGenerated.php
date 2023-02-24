<?php

namespace App\Events;

use App\Models\SearchStat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class SearchStatGenerated
{
    use Dispatchable;

    public function __construct(public SearchStat $keyword,
                                public int    $adsCount,
                                public int    $linksCount,
                                public float  $searchCount,
                                public string $rawResponse
                                )
    {

    }
}
