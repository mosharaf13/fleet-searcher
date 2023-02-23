<?php

namespace App;

use App\Exceptions\ChromeDriverException;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverCapabilities;
use Illuminate\Support\Facades\Log;

class Browser
{
    private WebDriverCapabilities $capabilities;
    private string $seleniumServerUrl;

    public function __construct()
    {
        $this->seleniumServerUrl = config('selenium.server_url');

        $options = new ChromeOptions();
        $options->addArguments(['lang=en-US', '--headless', '--disable-gpu', '--disable-extensions']);
        $this->capabilities = DesiredCapabilities::chrome();
        $this->capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
    }

    /**
     * @return WebDriver
     * @throws ChromeDriverException
     */
    public function getDriver(): Webdriver
    {
        try {
            return RemoteWebDriver::create($this->seleniumServerUrl, $this->capabilities);
        } catch (\Exception $exception) {
            Log::error("Error while starting Chrome driver " . $exception->getMessage());
            throw new ChromeDriverException("Error while starting Chrome driver " .  $exception->getMessage());
        }
    }
}
