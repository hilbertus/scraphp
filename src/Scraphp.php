<?php

namespace scraphp;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use scraphp\entity\Job;
use scraphp\entity\Status;
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
        $this->statusUpdate = new StatusUpdateNull();
    }

    public function run()
    {
        if($this->remoteWebDriver === null){
            $this->remoteWebDriver = RemoteWebDriver::create('http://127.0.0.1:8910', DesiredCapabilities::phantomjs());
        }


        $script = '';
        $script .= file_get_contents(__DIR__ . '/js/jquery-2.2.2.min.js');
        $script .= "\n\n";
        $script .= file_get_contents(__DIR__ . '/js/frontendscraper.js');
        $script .= "\n\n";

        while ($job = $this->jobBuffer->getJob()) {
            $status = $this->createInitStatus($job);
            $this->remoteWebDriver->get($job->url);
            $inject = $script;
            $inject .= file_get_contents($this->jobScriptDir . '/' . $job->script);
            $inject .= "\n\n return phantomScraper.getResult();";
            $res = $this->remoteWebDriver->executeScript($inject);
            $this->addJobs($res['jobs']);
            $this->addData($res['data']);
            $this->updateStatus($status);
        }
    }

    /**
     * @param Job $job
     * @return Status
     */
    private function createInitStatus($job)
    {
        $status = new Status();
        $status->url = $job->url;
        $status->injectedScript = $this->jobScriptDir . '/' . $job->script;
        $status->elapsedSeconds = - microtime(true);
        return $status;
    }

    /**
     * @param array $jobs
     */
    private function addJobs($jobs)
    {
        foreach ($jobs as $jobProp) {
            $job = Job::create($jobProp['url'], $jobProp['file']);
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
     * @param Status $status
     */
    private function updateStatus($status)
    {
        $status->remainingJobCount = $this->jobBuffer->getBufferedJobsCount();
        $status->doneJobCount = $this->jobBuffer->getTotalJobsCount() - $status->remainingJobCount;
        $status->elapsedSeconds += microtime(true);
        $this->statusUpdate->updateStatus($status);
    }

    /**
     * @return OutputBufferInterface
     */
    public function getOutputBuffer()
    {
        return $this->outputBuffer;
    }
}