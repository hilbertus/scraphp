<?php

namespace scraphp;

use scraphp\interfaces\OutputBufferInterface;

class OutputBufferJsonFile implements OutputBufferInterface
{

    public $dataUnitsPerFile = 1000;
    public $outputDir = '';
    protected $outputFilenameTemplate = 'data_part_%d.json';
    protected $fileCounter = 0;
    protected $buffer = [];

    public function addData($array)
    {
        $this->buffer[] = $array;
        if (count($this->buffer) >= $this->dataUnitsPerFile) {
            $this->writeJsonFile();
        }
    }

    public function writeJsonFile()
    {
        $this->fileCounter++;
        $filePath = $this->outputDir . '/' . sprintf($this->outputFilenameTemplate, $this->fileCounter);
        $jsonContent = json_encode($this->buffer, JSON_PRETTY_PRINT);
        file_put_contents($filePath, $jsonContent);
        $this->buffer = [];
    }

    public function __destruct()
    {
        if (count($this->buffer) > 0) {
            $this->writeJsonFile();
        }
    }

    public function removeDataFiles()
    {
        $globFiles = $this->outputDir . '/' . str_replace('%d', '*', $this->outputFilenameTemplate);
        array_map('unlink', glob($globFiles));
    }
}