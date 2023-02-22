<?php

namespace App\Contracts;

use Facebook\WebDriver\WebDriver;

interface Searcher
{
    public function search(string $url, WebDriver $driver, array $keywords): void;
}
