<?php

namespace Scraphp\Job;


class Job
{
    public string $url;

    public static function create(string $url): Job
    {
        $job = new Job();
        $job->url = $url;
        return $job;
    }
}