<?php


namespace Scraphp\Scraper;


use Scraphp\Job\ArrayJobBuffer;
use scraphp\Job\Job;
use Scraphp\Job\JobBufferInterface;
use Scraphp\Loader\LoaderInterface;
use Scraphp\Result\ArrayOutputBuffer;
use Scraphp\Result\OutputBufferInterface;
use Scraphp\Result\Result;

abstract class ScraperAbstract
{

    public function getLoader(): LoaderInterface
    {

        // TODO implement default Loader
        throw new \Exception('Function not implemented yet');
    }

    public function getJobBuffer(): JobBufferInterface
    {
        return new ArrayJobBuffer();
    }

    public function getOutputBuffer(): OutputBufferInterface
    {
        return new ArrayOutputBuffer();
    }


    abstract function getInitialJobs(): array;

    abstract function scrape(LoaderInterface $loader, Job $job): Result;

}