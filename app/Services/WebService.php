<?php

namespace App\Services;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverBy;

class WebService
{
    /**
     * Webdriver instance
     *
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * Callable stack
     *
     * @var mixed
     */
    protected $stack;

    /**
     * Singleton instance
     *
     * @var WebService
     */
    protected static $instance;

    /**
     * Open a webdriver instance
     *
     * @return WebService
     */
    public function open(): WebService
    {
        if (!$this->driver) {
            $options = new ChromeOptions();
            $options->setExperimentalOption('prefs', [
                "download.prompt_for_download" => false,
                "download.directory_upgrade"   => true,
                "safebrowsing.enabled"         => true,
            ]);
            $capabilities = DesiredCapabilities::chrome();
            $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

            $driver = RemoteWebDriver::create(
                config('services.selenium.host'),
                $capabilities
            );

            $driver->manage()->window()->setSize(new WebDriverDimension(1900, 1200));

            $this->driver = $driver;
        }

        return $this;
    }

    /**
     * Take a screen shot from the browser
     *
     * @return WebService
     */
    public function takeScreenshot()
    {
        $this->open()->driver->takeScreenshot(
            public_path('/screenshots/screenshot_'.time().'.png')
        );
        return $this;
    }

    /**
     * Find an element on page
     *
     * @param string $method
     * @param string $value
     * @return WebService
     */
    public function findElement(string $method, string $value): WebService
    {
        $this->stack = $this->driver->findElement(
            call_user_func_array(
                [WebDriverBy::class, $method],
                [$value]
            )
        );

        return $this;
    }

    /**
     * Handle click
     *
     * @return WebService
     */
    public function click(): WebService
    {
        if ($this->stack) {
            $this->stack = $this->stack->click();
            sleep(1);
        }

        return $this;
    }

    /**
     * Wait to perform next action
     *
     * @return WebService
     */
    public function wait($seconds): WebService
    {
        sleep($seconds);

        return $this;
    }

    /**
     * Input stack
     *
     * @return WebService
     */
    public function input(mixed $value): WebService
    {
        if ($this->stack) {
            $this->stack = $this->stack->sendKeys($value);
            sleep(1);
        }

        return $this;
    }

    /**
     * Navigate to url
     *
     * @param string $url
     * @return WebService
     */
    public function navigate(string $url): WebService
    {
        $this->driver->get($url);
        sleep(2);
        return $this;
    }

    /**
     * Close webdriver connection
     *
     * @return WebService
     */
    public function close(): WebService
    {
        if ($this->driver) {
            $this->driver->quit();
            $this->driver = null;
        }
        return $this;
    }

    /**
     * Get webservice instance
     *
     * @return WebService
     */
    static public function make(): WebService
    {
        if (!static::$instance) {
            static::$instance = resolve(static::class);
        }

        return static::$instance;
    }
}
