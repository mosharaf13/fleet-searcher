<?php

namespace App\Searchers;

use App\Browser;
use App\Contracts\Searcher;
use App\Events\KeywordsForScrappingFound;
use App\Events\SearchStatGenerated;
use App\Models\SearchStat;
use Facebook\WebDriver\Exception\PhpWebDriverExceptionInterface;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class GoogleSearcher implements Searcher, ShouldQueue
{
    use InteractsWithQueue;

    protected WebDriver $driver;
    protected $host = 'www.google.com';

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [10, 10, 15];
    }


    public function __construct(private Browser $browser, private string $url = 'https://www.google.com/search?hl=en&q=')
    {

    }

    /**
     * Handle the event.
     */
    public function handle(KeywordsForScrappingFound $event): void
    {
        sleep(1);
        $this->driver = $this->browser->getDriver();
        Log::debug("Driver created successfully");
        $this->search($event->searchStats);

        $this->driver->quit();
    }

    /**
     * Handle a job failure.
     */
    public function failed(KeywordsForScrappingFound $event, Throwable $exception): void
    {
        $this->updateSearchStatScrapStatus($event->searchStats);
        $keywords = array_map(function (SearchStat $searchStat) {
            return $searchStat->keyword;
        }, $event->searchStats);
        Log::error(
            "Exception happened while searching these keywords " .
            json_encode($keywords) . $exception->getMessage()
        );

        if ($exception instanceof PhpWebDriverExceptionInterface) {
            $this->driver->quit();
        }
    }

    private function updateSearchStatScrapStatus(array $searchStats)
    {
        foreach ($searchStats as $searchStat){
            $searchStat->scrap_status = SearchStat::SCRAP_STATUS_FAILED;
            $searchStat->save();
        }
    }

    /**
     * @param SearchStat[] $searchStats
     * @return void
     */
    public function search(array $searchStats): void
    {
        foreach ($searchStats as $searchStat) {
            $this->performSearch($searchStat);

            SearchStatGenerated::dispatch(
                $searchStat,
                $this->countAds(),
                $this->countLinks(),
                $this->extractTotalNumberOfResults(),
                $this->getRawResponse()
            );
        }
    }

    /**
     * @param SearchStat $searchStat
     * @return void
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeoutException
     */
    private function performSearch(SearchStat $searchStat): void
    {
        $this->driver->get($this->url . urlencode($searchStat->keyword));

        $this->driver->wait(5)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('result-stats'))
        );
    }

    private function countLinks(): ?int
    {
        return count($this->driver->findElements(WebDriverBy::cssSelector('a')));
    }

    private function countAds(): ?int
    {
        $adDiv = $this->driver->findElement(WebDriverBy::id('taw'));
        $adLinks = $adDiv->findElements(WebDriverBy::cssSelector('a'));

        $adLinks = array_map(function ($adLink) {
            if (filter_var($adLink->getAttribute('href'), FILTER_VALIDATE_URL)) {
                return parse_url($adLink->getAttribute('href'))['host'];
            }
            return null;
        }, $adLinks);

        $adLinks = array_values(array_filter($adLinks, function ($adLink) {
            return !is_null($adLink) && $adLink != $this->host;
        }));

        return count(array_count_values($adLinks));
    }

    private function extractTotalNumberOfResults(): ?float
    {
        $searchCount = $this->driver->findElement(WebDriverBy::id('result-stats'))->getText();

        // Extract the number of search results and return the counts
        preg_match('/([\d,]+) result/', $searchCount, $matches);
        return str_replace(',', '', $matches[1]);
    }

    private function getRawResponse(): ?string
    {
        return $this->driver->getPageSource();
    }
}
