<?php

namespace Scraphp\Job;


class ArrayJobBuffer implements JobBufferInterface
{
    protected array $existingJobUrls = [];
    /**
     * @var Job[]
     */
    protected array $jobs = [];


    /**
     * @param Job[] $jobs
     */
    public function addJobsOnce(array $jobs)
    {
        foreach ($jobs as $job) {
            $this->addJobOnce($job);
        }
    }

    /**
     * @param Job $job
     */
    private function addJobOnce($job)
    {
        if (array_key_exists($job->url, $this->existingJobUrls)) {
            return;
        }
        $this->jobs[$job->url] = $job;
        $this->existingJobUrls[$job->url] = null;
    }

    /**
     * @return Job|null
     */
    public function getNextJob()
    {
        return reset($this->jobs);
    }

    public function getNumberOfRemainingJobs(): int
    {
        return count($this->jobs);
    }

    public function getNumberOfTotalJob(): int
    {
        return count($this->existingJobUrls);
    }

    public function markDoneJob(Job $job)
    {
        if (!key_exists($job->url, $this->jobs)) {
            return;
        }
        unset($this->jobs[$job->url]);
    }
}