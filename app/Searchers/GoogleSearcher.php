<?php

namespace App\Searchers;

use App\Contracts\Searcher;
use App\Events\SearchStatGenerated;
use App\Traits\ManipulatesDom;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Support\Facades\Auth;


class GoogleSearcher implements Searcher
{
    use ManipulatesDom;

    protected WebDriver $driver;
    protected $host = 'www.google.com';

    /**
     * @param WebDriver $driver
     * @param array $keywords
     * @return void
     */
    public function search(string $url, WebDriver $driver, array $keywords): void
    {
        $this->driver = $driver;
        foreach ($keywords as $keyword) {
            $this->performSearch($url, $keyword);

            SearchStatGenerated::dispatch(
                $keyword,
                $this->countAds(),
                $this->countLinks(),
                $this->extractTotalNumberOfResults(),
                $this->getRawResponse(),
                Auth::id()
            );
        }
    }

    private function performSearch(string $url, string $keyword): void
    {
        $this->driver->get($url . urlencode($keyword));

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

    /**
     * return raw response after making all the relative urls absolute in the page
     * @return string|null
     */
    private function getRawResponse(): ?string
    {
        $url = $this->driver->getCurrentURL();
        return $this->makeRelativeUrlsAbsolute(
            parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST)
        );
    }

    private function makeRelativeUrlsAbsolute($currentUrl)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->driver->getPageSource());

        $this->changeHrefOfLinks($dom, $currentUrl);
        $this->changeSrcOfImgs($dom, $currentUrl);
        $this->changeSrcAttributes('script', $dom, $currentUrl);

        return $dom->saveHTML();
    }
}
