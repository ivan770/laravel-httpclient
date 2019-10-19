<?php

namespace Ivan770\HttpClient;

use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class BrowserKitRequest
{
    /**
     * Symfony BrowserKit instance
     *
     * @var HttpBrowser
     */
    protected $browserKit;

    /**
     * History instance
     *
     * @var History
     */
    protected $history;

    /**
     * CookieJar instance
     *
     * @var CookieJar
     */
    protected $cookieJar;

    /**
     * Enable Symfony BrowserKit support
     *
     * @var bool
     */
    protected $enableBrowserKit = false;

    /**
     * Create BrowserKit instance on current HttpClient instance
     *
     * @param HttpClient $client
     * @param History $history
     * @param CookieJar $cookieJar
     * @return HttpBrowser
     */
    protected function wrapBrowserKit(HttpClient $client, History $history, CookieJar $cookieJar)
    {
        $this->history = $history;
        $this->cookieJar = $cookieJar;

        return $this->browserKit = new HttpBrowser($client->getSymfonyClient(), $this->history, $this->cookieJar);
    }

    /**
     * Request resource via BrowserKit
     *
     * @param HttpClient $client
     * @param $resource
     * @param $name
     * @return Crawler
     */
    protected function passToBrowserKit($resource, $name)
    {
        return $this->browserKit->request($resource, $name);
    }

    /**
     * Get Symfony BrowserKit instance
     *
     * @return HttpBrowser
     */
    public function getBrowserKit()
    {
        return $this->browserKit;
    }

    /**
     * Get CookieJar instance
     *
     * @return CookieJar
     */
    public function getCookieJar()
    {
        return $this->cookieJar;
    }

    /**
     * Get History instance
     *
     * @return History
     */
    public function getHistory()
    {
        return $this->history;
    }
}