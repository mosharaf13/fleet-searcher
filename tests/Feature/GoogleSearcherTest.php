<?php

namespace Tests\Feature;

use App\Browser;
use App\Events\SearchStatGenerated;
use App\Models\User;
use App\Searchers\GoogleSearcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Symfony\Component\Process\Process;


class GoogleSearcherTest extends TestCase
{

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

        $driver = (new Browser())->getDriver();
        sleep(5);
        $keywords = ['empty'];

        $url = 'http://host.docker.internal:8800/';

        $search = new GoogleSearcher();
        $search->search($url, $driver, $keywords);

        foreach ($keywords as $keyword) {
            Event::assertDispatched(SearchStatGenerated::class, function ($event) use ($keyword) {
                return $event->keyword === $keyword;
            });
        }
    }

    public function test_it_fetches_url_by_keyword_and_counts_correctly()
    {
        Event::fake();

        // Run the code that should dispatch the event
        $driver = (new Browser())->getDriver();
        sleep(5);

        $keywords = ['cheap flights'];
        $url = 'http://host.docker.internal:8800/';
        $searcher = new GoogleSearcher();
        $searcher->search($url, $driver, $keywords);

        foreach ($keywords as $keyword) {
            Event::assertDispatched(SearchStatGenerated::class, function ($event) use ($keyword) {
                return $event->keyword == $keyword
                    && $event->adsCount == 3
                    && $event->linksCount == 52
                    && $event->searchCount == 265000000;
            });
        }
    }
}
