<?php


namespace Scraphp\Result;


interface OutputBufferInterface
{
    function addRecords(array $records);

    function addRecordsIndexedByRecordId(array $records);
}