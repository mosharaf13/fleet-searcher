<?php

namespace App\Storages;

use App\Contracts\Storage;
use App\Models\SearchStat;
use Illuminate\Support\Facades\Auth;

class DbStorage implements Storage
{
    /**
     * @param array $keywords
     * @return SearchStat[]
     */
    public function storeKeywords(array $keywords): array
    {
        $searchStats = [];
        foreach ($keywords as $keyword) {
            $searchStats[] = SearchStat::create([
                'keyword' => $keyword,
                'scrap_status' => SearchStat::SCRAP_STATUS_INITIALIZED,
                'user_id' => Auth::id()
            ]);
        }

        return $searchStats;
    }
}
