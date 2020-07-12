<?php


namespace Scraphp\Job;


interface JobBufferInterface
{
    /**
     * @param Job[] $jobs
     */
    public function addJobsOnce(array $jobs);

    /**
     * @return Job|null
     */
    public function getNextJob();

    public function getNumberOfRemainingJobs(): int;

    public function getNumberOfTotalJob(): int;

    public function markDoneJob(Job $job);
}