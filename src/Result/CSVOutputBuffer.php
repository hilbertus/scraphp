<?php


namespace Scraphp\Result;


class CSVOutputBuffer implements OutputBufferInterface
{
    private $fileHandle;
    private bool $headerAlreadyExists = false;

    private array $existingRecordIds = [];

    public function __construct(string $csvPath, bool $overwriteCsv = false)
    {
        if ($overwriteCsv) {
            $this->fileHandle = fopen($csvPath, 'w+');
        } else {
            if (file_exists($csvPath) && filesize($csvPath) > 1) {
                $this->headerAlreadyExists = true;
            }
            $this->fileHandle = fopen($csvPath, 'a+');
        }
    }

    function addRecords(array $records)
    {
        foreach ($records as $record) {
            $this->writeRecord2File($record);
        }
    }

    private function writeRecord2File($record)
    {
        $dataArr = [];
        if (is_object($record)) {
            $dataArr = json_decode(json_encode($record), true);
        } elseif (is_array($record)) {
            $dataArr = $record;
        } else {
            $dataArr = [$record];
        }
        if (!$this->headerAlreadyExists) {
            fputcsv($this->fileHandle, array_keys($dataArr));
            $this->headerAlreadyExists = true;
        }
        fputcsv($this->fileHandle, $dataArr);
    }

    function addRecordsIndexedByRecordId(array $records)
    {
        foreach ($records as $id => $record) {
            if (key_exists($id, $this->existingRecordIds)) {
                continue;
            }
            $this->writeRecord2File($record);
            $this->existingRecordIds[$id] = $id;
        }
    }

    public function __destruct()
    {
        fclose($this->fileHandle);
    }
}