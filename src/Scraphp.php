<?php

namespace scraphp;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use scraphp\entity\Job;
use scraphp\interfaces\StatusUpdateInterface;

class Scraphp
{

    /**
     * @var JobBuffer
     */
    protected $jobBuffer;
    /**
     * @var OutputBufferInterface
     */
    protected $outputBuffer;

    /**
     * @var string
     */
    protected $jobScriptDir = '';

    /**
     * @var RemoteWebDriver
     */
    public $remoteWebDriver;
    /**
     * @var StatusUpdateInterface
     */
    public $statusUpdate;

    /**
     * Scraphp constructor.
     * @param JobBuffer $jobBuffer
     * @param OutputBufferInterface $outputBuffer
     * @param string $jobScriptDir
     */
    public function __construct($jobBuffer, $outputBuffer, $jobScriptDir)
    {
        $this->jobBuffer = $jobBuffer;
        $this->outputBuffer = $outputBuffer;
        $this->jobScriptDir = $jobScriptDir;
    }

    public function run()
    {
        $driver = RemoteWebDriver::create('http://127.0.0.1:8910', DesiredCapabilities::phantomjs());

        $script = '';
        $script .= file_get_contents(__DIR__ . '/js/jquery-2.2.2.min.js');
        $script .= "\n\n";
        $script .= file_get_contents(__DIR__ . '/js/frontendscraper.js');
        $script .= "\n\n";

        while ($job = $this->jobBuffer->getJob()) {
            $driver->get($job->url);
            $inject = $script;
            $inject .= file_get_contents($this->jobScriptDir . '/' . $job->script);
            $inject .= "\n\n return phantomScraper.getResult();";
            $res = $driver->executeScript($inject);
            $this->addJobs($res['jobs']);
            $this->addData($res['data']);
        }
    }

    /**
     * @param array $jobs
     */
    private function addJobs($jobs)
    {
        foreach ($jobs as $jobProp) {
            $job = new Job();
            $job->url = $jobProp['url'];
            $job->script = $jobProp['file'];
            $this->jobBuffer->addJob($job);
        }
    }

    /**
     * @param array $dataArr
     */
    private function addData($dataArr)
    {
        foreach ($dataArr as $data) {
            $this->outputBuffer->addData($data);
        }
    }

    /**
     * @return OutputBufferInterface
     */
    public function getOutputBuffer()
    {
        return $this->outputBuffer;
    }
}