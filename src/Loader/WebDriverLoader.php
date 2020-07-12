<?php


namespace Scraphp\Loader;


use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Scraphp\Helper\Console;

class WebDriverLoader implements LoaderInterface
{
    private RemoteWebDriver $remoteWebDriver;
    private bool $keepBrowserOpenUntilConfirmed = false;

    public function __construct($webdriverHost = '127.0.0.1', $port = 4444, ?DesiredCapabilities $desiredCapabilities = null)
    {
        $this->remoteWebDriver = RemoteWebDriver::create($webdriverHost . ':' . $port, $desiredCapabilities);
    }

    public function keepBrowserOpenUntilConfirmed(bool $bool = true)
    {
        $this->keepBrowserOpenUntilConfirmed = $bool;
    }

    public function openUrl(string $url)
    {
        $this->remoteWebDriver->get($url);
    }

    public function getText(string $selector): array
    {
        $webDriverElements = $this->remoteWebDriver->findElements(WebDriverBy::cssSelector($selector));
        $result = [];
        foreach ($webDriverElements as $webDriverElement) {
            $result[] = $webDriverElement->getText();
        }
        return $result;
    }

    public function getIterativeText(string $selector4Iteration, array $subSelectionsIndexedByNames): array
    {
        $webDriverElements = $this->remoteWebDriver->findElements(WebDriverBy::cssSelector($selector4Iteration));
        $result = [];
        foreach ($webDriverElements as $webDriverElement) {
            $row = [];
            foreach ($subSelectionsIndexedByNames as $name => $subSelector) {
                $subWebDriverElement = $webDriverElement->findElement(WebDriverBy::cssSelector($subSelector));
                $row[$name] = $subWebDriverElement->getText();
            }
            $result[] = $row;
        }
        return $result;
    }

    public function getInnerHTML(string $selector): array
    {
        $webDriverElements = $this->remoteWebDriver->findElements(WebDriverBy::cssSelector($selector));
        $result = [];
        foreach ($webDriverElements as $webDriverElement) {
            $result[] = $this->remoteWebDriver->executeScript('return arguments[0].innerHTML;', [$webDriverElement]);
        }
        return $result;
    }

    public function getAttr(string $selector, string $attribute): array
    {
        $webDriverElements = $this->remoteWebDriver->findElements(WebDriverBy::cssSelector($selector));
        $result = [];
        foreach ($webDriverElements as $webDriverElement) {
            $result[] = $webDriverElement->getAttribute($attribute);
        }
        return $result;
    }

    public function __destruct()
    {
        if ($this->keepBrowserOpenUntilConfirmed) {
            Console::inputRequest("Press any key to continue: ");
        }
        $this->remoteWebDriver->quit();
    }

}