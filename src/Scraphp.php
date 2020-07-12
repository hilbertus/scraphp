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
        $logger = $scraper->getStatusLogger();
        while ($job = $jobBuffer->getNextJob()) {
            $logger->log($job, $jobBuffer);
            $loader->openUrl($job->url);
            $result = $scraper->scrape($loader, $job);
            $outputBuffer->addRecords($result->getRecords());
            $outputBuffer->addRecordsIndexedByRecordId($result->getUniqueRecordsIndexedById());
            $jobBuffer->addJobsOnce($result->getJobs());
            $jobBuffer->markDoneJob($job);
        }
        return $outputBuffer;
    }
}