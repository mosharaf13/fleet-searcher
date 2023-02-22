<?php

namespace App\Http\Controllers;

use App\Models\SearchStat;
use Illuminate\Http\Request;

class SearchStatController extends Controller
{
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

        return response()->json($searchStat->orderBy('created_at', 'desc')->paginate(10));
    }
}
