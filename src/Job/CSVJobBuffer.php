<?php


namespace Scraphp\Job;


class CSVJobBuffer implements JobBufferInterface
{
    const JOB_STATUS_DONE = 'done';
    const JOB_STATUS_PENDING = 'pending';

    /**
     * @var Job[]
     */
    protected array $existingJobUrls = [];
    protected array $jobs = [];
    protected string $path;
    protected $handle;

    public function __construct($path)
    {
        $this->path = $path;
        if (!file_exists($path)) {
            $this->initFile();
        }
        $this->handle = fopen($path, 'a+');
    }

    private function initFile()
    {
        $handle = fopen($this->path, 'w+');
        $row = $this->createRow('', '');
        fputcsv($handle, array_keys($row));
        fclose($handle);
    }

    public function initFromCSV()
    {
        $handle = fopen($this->path, 'r+');
        $firstRow = true;
        while ($row = fgetcsv($handle)) {
            if ($firstRow) {
                $firstRow = false;
                continue;
            }
            $job = Job::create($row[0]);
            $status = $row[1];
            $this->existingJobUrls[$job->url] = null;
            if ($status !== self::JOB_STATUS_DONE) {
                $this->jobs[$job->url] = $job;
            } elseif (key_exists($job->url, $this->jobs)) {
                unset($this->jobs[$job->url]);
            }
        }
        fclose($handle);
    }

    private function createRow(string $url, string $status)
    {
        return ['url' => $url, 'status' => $status];
    }

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
        $row = $this->createRow($job->url, self::JOB_STATUS_PENDING);
        fputcsv($this->handle, $row);
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
        $row = $this->createRow($job->url, self::JOB_STATUS_DONE);
        fputcsv($this->handle, $row);
        unset($this->jobs[$job->url]);
    }
}