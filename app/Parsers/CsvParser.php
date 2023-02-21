<?php

namespace App\Parsers;

use App\Contracts\FileInputParser;
use App\Exceptions\InvalidKeywordException;
use App\Exceptions\TooManyKeywordsException;
use Illuminate\Http\UploadedFile;

class CsvParser implements FileInputParser
{
    private $keywordMaxLength;
    private $maxNumberOfKeywords;

    public function __construct()
    {
        $this->keywordMaxLength = 512;
        $this->maxNumberOfKeywords = 100;
    }

    /**
     * @param UploadedFile $file
     * @return array|string []
     * @throws InvalidKeywordException
     * @throws TooManyKeywordsException
     */
    public function parse(UploadedFile $file): array
    {
        $keywords = [];

        $handle = fopen($file, 'r');

        if ($handle !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                foreach ($data as $keyword) {
                    $keyword = trim($keyword);
                    if (empty($keyword)) {
                        continue;
                    }
                    if (strlen($keyword) > $this->keywordMaxLength) {
                        throw new InvalidKeywordException("Each keyword must be less than or equal to 512 characters in length");
                    }
                    $keywords[] = $keyword;
                }
            }

            fclose($handle);
        }

        if (count($keywords) > $this->maxNumberOfKeywords) {
            throw new TooManyKeywordsException('CSV file must contain less than or equal to 100 keywords');
        }

        return $keywords;
    }

}
