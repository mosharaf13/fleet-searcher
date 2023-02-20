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
    public function index()
    {
        return response()->json(SearchStat::orderBy('created_at', 'desc')->paginate(10));
    }
}
