<?php

namespace Tests\Feature;

use App\Browser;
use App\Events\KeywordsForScrappingFound;
use App\Events\SearchStatGenerated;
use App\Models\SearchStat;
use App\Models\User;
use App\Searchers\GoogleSearcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Symfony\Component\Process\Process;


class GoogleSearcherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Auth::login($user);

        $this->process = new Process(['php', '-S', '0.0.0.0:8800', '-t', __DIR__ . '/files']);
        $this->process->start();
        sleep(2);

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->process->stop();
        sleep(2);
    }

    public function test_it_dispatches_search_stats_generated_event()
    {
        Event::fake();

        sleep(5);

        $searchStats = [
            new SearchStat([
                'keyword' => 'empty',
                'scrap_status' => SearchStat::SCRAP_STATUS_INITIALIZED,
                'user_id' => 1
            ])
        ];

        $event = new KeywordsForScrappingFound($searchStats);
        $url = 'http://host.docker.internal:8800/';
        $search = new GoogleSearcher(new Browser(), $url);
        $search->handle($event);

        foreach ($searchStats as $searchStat) {
            Event::assertDispatched(SearchStatGenerated::class, function ($event) use ($searchStat) {
                return $event->keyword === $searchStat;
            });
        }
    }

    public function test_it_fetches_url_by_keyword_and_counts_correctly()
    {
        Event::fake();

        // Run the code that should dispatch the event
        sleep(5);


        $searchStats = [
            new SearchStat([
                'keyword' => 'cheap flights',
                'scrap_status' => SearchStat::SCRAP_STATUS_INITIALIZED,
                'user_id' => 1
            ]),
        ];

        $event = new KeywordsForScrappingFound($searchStats);

        $url = 'http://host.docker.internal:8800/';
        $searcher = new GoogleSearcher(new Browser(), $url);
        $searcher->handle($event);

        foreach ($searchStats as $searchStat) {
            Event::assertDispatched(SearchStatGenerated::class, function ($event) use ($searchStat) {
                return $event->keyword == $searchStat
                    && $event->adsCount == 3
                    && $event->linksCount == 52
                    && $event->searchCount == 265000000;
            });
        }
    }
}
