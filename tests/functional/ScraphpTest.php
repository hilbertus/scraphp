<?php

namespace functional;


use scraphp\OutputBufferArray;
use scraphp\Entity\Job;
use scraphp\JobBuffer;
use scraphp\Scraphp;
use scraphp\StatusUpdateEcho;

class ScraphpTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $job = Job::create('https://en.wikipedia.org/wiki/Main_Page', 'index.js');

        $jobBuffer = new JobBuffer();
        $jobBuffer->addJob($job);

        $outputBuffer = new OutputBufferArray();

        $jobScriptDir = __DIR__ . '/wikiScraper';

        $scraphp = new Scraphp($jobBuffer, $outputBuffer, $jobScriptDir);
        $scraphp->statusUpdate = new StatusUpdateEcho();
        $scraphp->run();
        $filledOutputBuffer = $scraphp->getOutputBuffer();

        $expectedResult = [
            [
                'title' => 'Wikipedia, the free encyclopedia',
                'moreData' => 42
            ],
            [
                'title' => 'Wikipedia:About - Wikipedia, the free encyclopedia',
                'isThisATest' => true
            ],
        ];

        $this->assertEquals(json_encode($expectedResult), json_encode($filledOutputBuffer->getData()));

        $this->assertTrue(true);
    }
}