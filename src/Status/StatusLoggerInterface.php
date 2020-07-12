<?php


namespace Scraphp\Status;


use Scraphp\Job\Job;
use Scraphp\Job\JobBufferInterface;

interface StatusLoggerInterface
{
    public function log(Job $currentJob, JobBufferInterface $jobBuffer);
}