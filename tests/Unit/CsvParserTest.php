<?php

namespace Tests\Unit;

use App\Exceptions\TooManyKeywordsException;
use App\Parsers\CsvParser;
use App\Exceptions\InvalidKeywordException;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class CsvParserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new CsvParser();
    }

    public function testParseReturnsEmptyArrayWhenGivenEmptyFile()
    {
        $file = new UploadedFile(__DIR__ . '/files/empty.csv', 'empty.csv');

        $keywords = $this->parser->parse($file);

        $this->assertIsArray($keywords);
        $this->assertEmpty($keywords);
    }

    public function test_valid_csv_parses_correctly()
    {
        $file = new UploadedFile(__DIR__ . '/files/valid.csv', 'valid.csv');

        $result = $this->parser->parse($file);

        $this->assertEquals(['buy shoes online', 'cheap flights', 'local pizza delivery'], $result);
    }

    public function test_invalid_csv_throws_exception_when_keywords_exceeds_max_length()
    {
        $this->expectException(InvalidKeywordException::class);

        $file = new UploadedFile(__DIR__ . '/files/invalid.csv', 'invalid.csv');

        $this->parser->parse($file);
    }

    public function test_invalid_csv_throws_exception_when_number_of_keywords_exceeds_max_allowed_number()
    {
        $this->expectException(TooManyKeywordsException::class);

        $file = new UploadedFile(__DIR__ . '/files/long.csv', 'long.csv');

        $this->parser->parse($file);
    }
}
