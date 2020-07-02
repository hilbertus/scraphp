<?php


namespace Scraphp;


use Scraphp\Result\OutputBufferInterface;
use Scraphp\Scraper\ScraperAbstract;

class Scraphp
{
    public static function start(ScraperAbstract $scraper): OutputBufferInterface
    {
        $loader = $scraper->getLoader();
        $outputBuffer = $scraper->getOutputBuffer();
        $jobBuffer = $scraper->getJobBuffer();
        $jobs = $scraper->getInitialJobs();
        $jobBuffer->addJobsOnce($jobs);
        while ($job = $jobBuffer->getNextJob()) {
            // TODO Status ausgeben
            $loader->openUrl($job->url);
            $result = $scraper->scrape($loader, $job);
            $outputBuffer->addRecords($result->getRecords());
            $outputBuffer->addRecordsIndexedByRecordId($result->getUniqueRecordsIndexedById());
            $jobBuffer->addJobsOnce($result->getJobs());
        }
        return $outputBuffer;
    }
}