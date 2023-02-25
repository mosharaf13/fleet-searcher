<?php

namespace App\Http\Controllers;

use App\Contracts\FileInputParser;
use App\Contracts\SearchService;
use App\Contracts\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearcherController extends Controller
{
    public function __construct(
        private FileInputParser $fileInputParser,
        private SearchService   $searchService,
        private Storage         $storage
    )
    {
    }

    /**
     * @param Request $request
     * @todo Handle Chromedriver Start Exception
     *
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keywords' => 'required|file|mimetypes:text/csv,text/plain|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $keywords = $this->fileInputParser->parse($request->file('keywords'));

            $searchStatus = $this->storage->storeKeywords($keywords);
            $this->searchService->search($searchStatus);

            return response()->json("Keywords uploaded successfully");
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }
}
