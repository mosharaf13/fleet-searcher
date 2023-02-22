<?php

namespace App\Http\Controllers;

use App\Browser;
use App\Contracts\FileInputParser;
use App\Contracts\Searcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearcherController extends Controller
{
    public function __construct(
        private FileInputParser $fileInputParser,
        private Browser         $browser,
        private Searcher        $searcher
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

        $keywords = $this->fileInputParser->parse($request->file('keywords'));

        foreach (array_chunk($keywords, 10) as $keywordsChunk) {
            $driver = $this->browser->getDriver();
            $this->searcher->search($driver, $keywordsChunk);
            $driver->quit();
        }
        return response()->json("Keywords uploaded successfully");
    }
}
