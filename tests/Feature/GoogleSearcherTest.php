<?php

namespace Tests\Feature;

use App\Events\SearchStatGenerated;
use Facebook\WebDriver\WebDriver;

use App\Searchers\GoogleSearcher;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Tests\TestCase;

class GoogleSearcherTest extends TestCase
{
    /** @test */
    public function it_should_dispatch_search_stats_generated_event()
    {
        $driver = \Mockery::mock(WebDriver::class);
        $keywords = ['apple', 'banana', 'orange'];

        // expect the dispatch method to be called for each keyword
        SearchStatGenerated::shouldReceive('dispatch')
            ->times(count($keywords));

        $search = new GoogleSearcher();
        $search->search($driver, $keywords);
    }

    /** @test */
    public function it_can_search_for_one_keyword()
    {
        $driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            ['platform' => 'LINUX', 'browserName' => 'chrome']
        );

        $searcher = new GoogleSearcher();
        $searcher->search($driver, ['apple']);

        $driver->quit();
    }

    /** @test */
    public function it_can_search_for_multiple_keywords()
    {
        $driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            ['platform' => 'LINUX', 'browserName' => 'chrome']
        );

        $searcher = new GoogleSearcher();
        $searcher->search($driver, ['apple', 'banana', 'cherry']);

        $driver->quit();
    }

    /** @test */
    public function it_handles_empty_response()
    {
        $driver = \Mockery::mock(RemoteWebDriver::class);
        $driver->shouldReceive('getPageSource')
            ->andReturn('');

        $searcher = new GoogleSearcher();
        $searcher->search($driver, ['keyword that returns empty response']);

        $driver->shouldHaveReceived('getPageSource')->times(1);
    }
}
