<?php

namespace scraphp;


class JobBuffer
{
    protected $existingJobUrls = [];
    /**
     * @var Job[]
     */
    protected $jobs = [];

    /**
     * @param Job $job
     */
    public function addJob($job){
        if(array_key_exists($job->url, $this->existingJobUrls)){
            return;
        }
        array_push($this->jobs, $job);
        $this->existingJobUrls[$job->url] = null;
    }

    /**
     * @return Job
     */
    public function getJob(){
        return array_shift($this->jobs);
    }

    /**
     * @return int
     */
    public function getBufferedJobsCount()
    {
        return count($this->jobs);
    }

    /**
     * @return int
     */
    public function getTotalJobsCount()
    {
        return count($this->existingJobUrls);
    }
}