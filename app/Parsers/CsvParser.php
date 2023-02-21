<?php

namespace App\Parsers;

use App\Contracts\FileInputParser;
use App\Exceptions\InvalidKeywordException;
use App\Exceptions\TooManyKeywordsException;
use Illuminate\Http\UploadedFile;

class CsvParser implements FileInputParser
{
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
                    if (!(is_string($keyword) && strlen($keyword) <= 255)) {
                        throw new InvalidKeywordException("Each keyword must be a valid string and less than 512 characters in length");
                    }
                    if (empty(trim($keyword))) {
                        continue;
                    }
                    $keywords[] = $keyword;
                }
            }

            fclose($handle);
        }

        if (count($keywords) > 100) {
            throw new TooManyKeywordsException('CSV file must contain less than 100 keywords');
        }

        return $keywords;
    }
}
