<?php


namespace Scraphp\Status;


use Scraphp\Job\Job;
use Scraphp\Job\JobBufferInterface;

class EchoStatusLogger implements StatusLoggerInterface
{

    public function log(Job $currentJob, JobBufferInterface $jobBuffer)
    {
        $numberAllJobs = $jobBuffer->getNumberOfTotalJob();
        $numberDoneJobs = $numberAllJobs - $jobBuffer->getNumberOfRemainingJobs();
        echo sprintf("%' 6d/%' 6d    %s\n", $numberDoneJobs, $numberAllJobs, $currentJob->url);
    }
}