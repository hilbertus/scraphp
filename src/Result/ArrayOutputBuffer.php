<?php


namespace Scraphp\Result;


class ArrayOutputBuffer implements OutputBufferInterface
{

    protected array $records = [];
    protected array $uniqueRecords = [];

    function addRecords(array $records)
    {
        foreach ($records as $record) {
            $this->records[] = $record;
        }
    }

    function addRecordsIndexedByRecordId(array $records)
    {
        foreach ($records as $recordId => $record) {
            $this->uniqueRecords[$recordId] = $record;
        }
    }
}