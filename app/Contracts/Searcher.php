<?php

namespace App\Contracts;

use Facebook\WebDriver\WebDriver;

interface Searcher
{
    public function search(WebDriver $driver, array $keywords): void;
}
