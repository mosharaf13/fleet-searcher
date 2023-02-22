<?php

namespace App\Http\Controllers;

use App\Models\SearchStat;
use Illuminate\Http\Request;

class SearchStatController extends Controller
{
    public function __construct(private $paginationSize = 8)
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $searchStat = SearchStat::query();

        if ($request->has('keyword')) {
            $searchStat->where('keyword', 'like', '%' . $request->get('keyword') . '%');
        }

        return response()->json(
            $searchStat->orderBy('created_at', 'desc')
                ->paginate($this->paginationSize)
        );
    }

    public function keywords(Request $request)
    {
        $searchStat = SearchStat::query();

        if ($request->has('keyword')) {
            $searchStat->where('keyword', 'like', '%' . $request->get('keyword') . '%');
        }

        return response()->json(
            $searchStat->select('keyword')
                ->orderBy('created_at', 'desc')
                ->paginate($this->paginationSize)
        );
    }
}
