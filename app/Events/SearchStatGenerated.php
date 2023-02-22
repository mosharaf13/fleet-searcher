<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SearchStatGenerated
{
    use Dispatchable;

    public function __construct(public string $keyword,
                                public int    $adsCount,
                                public int    $linksCount,
                                public float  $searchCount,
                                public string $rawResponse)
    {
    }
}
