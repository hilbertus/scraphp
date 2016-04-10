<?php

namespace scraphp;


class JobBufferTest extends \PHPUnit_Framework_TestCase
{

    public function testJobBuffer()
    {
        $jobBuffer = new JobBuffer();
        $this->assertEquals(0, $jobBuffer->getTotalJobsCount());
        $this->assertEquals(0, $jobBuffer->getBufferedJobsCount());

        $job = Job::create('http://any-site.com', 'any-script.js');
        $jobBuffer->addJob($job);
        $this->assertEquals(1, $jobBuffer->getTotalJobsCount());
        $this->assertEquals(1, $jobBuffer->getBufferedJobsCount());

        $job2 = Job::create('http://any-site-2.com', 'any-script.js');
        $jobBuffer->addJob($job2);
        $this->assertEquals(2, $jobBuffer->getTotalJobsCount());
        $this->assertEquals(2, $jobBuffer->getBufferedJobsCount());

        $jobBuffer->addJob($job2);
        $this->assertEquals(2, $jobBuffer->getTotalJobsCount());
        $this->assertEquals(2, $jobBuffer->getBufferedJobsCount());

        $firstJob = $jobBuffer->getJob();
        $this->assertEquals($job, $firstJob);
        $this->assertEquals(2, $jobBuffer->getTotalJobsCount());
        $this->assertEquals(1, $jobBuffer->getBufferedJobsCount());

        $secondJob = $jobBuffer->getJob();
        $this->assertEquals($job2, $secondJob);
        $this->assertEquals(2, $jobBuffer->getTotalJobsCount());
        $this->assertEquals(0, $jobBuffer->getBufferedJobsCount());

        $this->assertNull($jobBuffer->getJob());
    }
}