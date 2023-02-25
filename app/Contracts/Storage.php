<?php

namespace App\Contracts;

use App\Models\SearchStat;

interface Storage
{
    /**
     * @param array $keywords
     * @return SearchStat[]
     */
    public function storeKeywords(array $keywords): array;
}
