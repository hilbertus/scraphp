<?php


namespace Scraphp\Result;


use scraphp\Job\Job;

class Result
{

    protected array $jobs = [];
    protected array $uniqueRecords = [];
    protected array $records = [];

    /**
     * @param Job[] $jobs
     */
    public function addJobs(array $jobs)
    {
        foreach ($jobs as $job) {
            $this->jobs[] = $job;
        }
    }

    /**
     * @return Job[]
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }

    /**
     * @param mixed $record
     * @param null|mixed $recordId
     */
    public function addRecord($record, $recordId = null)
    {
        if ($recordId !== null) {
            $this->uniqueRecords[$recordId] = $record;
        } else {
            $this->records[] = $record;
        }
    }

    public function addRecords(array $records, bool $useIndexAsRecordId = false)
    {
        foreach ($records as $arrIndex => $record) {
            if ($useIndexAsRecordId) {
                $this->addRecord($record, $arrIndex);
            } else {
                $this->addRecord($record);
            }
        }
    }

    public function getUniqueRecordsIndexedById(): array
    {
        return $this->uniqueRecords;
    }

    public function getRecords(): array
    {
        return $this->records;
    }
}