<?php

namespace App\Http\Controllers;

use App\Browser;
use App\Contracts\FileInputParser;
use App\Contracts\Searcher;
use Facebook\WebDriver\Exception\PhpWebDriverExceptionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $keywords = $this->fileInputParser->parse($request->file('keywords'));

            foreach (array_chunk($keywords, 10) as $keywordsChunk) {
                $this->searchChunk($keywordsChunk);
            }
            return response()->json("Keywords uploaded successfully");
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }

    }

    private function searchChunk($keywordsChunk)
    {
        $driver = null;
        try {
            $driver = $this->browser->getDriver();
            $this->searcher->search($driver, $keywordsChunk);
        } catch (\Exception $exception) {
            Log::error("Exception happened while searching these keywords " . json_encode($keywordsChunk) . $exception->getMessage());
            if($exception instanceof PhpWebDriverExceptionInterface){
                $driver->quit();
            }
            sleep(2);
        }

    }
}
