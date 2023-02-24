<?php

namespace App\Contracts;

use Facebook\WebDriver\WebDriver;

interface Searcher
{
    public function search(array $keywords): void;
}
