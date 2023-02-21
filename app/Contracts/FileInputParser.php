<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface FileInputParser
{
    /**
     * @param UploadedFile $file
     * @return string []
     */
    public function parse(UploadedFile $file): array;
}
