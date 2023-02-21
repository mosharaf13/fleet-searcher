<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearcherController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keywords' => 'required|file|mimetypes:text/csv,text/plain|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        return $request->file('keywords');
    }
}
