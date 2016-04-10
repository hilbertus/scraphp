<?php

namespace scraphp;


class OutputBufferJsonFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $dir;

    protected function setUp()
    {
        $this->dir = dirname(__DIR__) . '/data';
        $this->clearDataDir();
        mkdir($this->dir);

    }

    private function clearDataDir(){
        array_map('unlink', glob("$this->dir/*.*"));
        if(is_dir($this->dir)){
            rmdir($this->dir);
        }
    }

    protected function tearDown()
    {
        $this->clearDataDir();
    }

    public function testJsonFileDataBuffer()
    {
        $jsonBuffer = $this->getCreatedJsonBuffer();

        $dummyData = [
            'key1' => 'val1',
            'key2' => ['subkey2_1' => 'subval2_1', 'subkey2_2' => 'subval2_2', 'subkey2_3' => 'subval2_3'],
            'key3' => 'val3'
        ];

        $jsonBuffer->addData($dummyData);
        $filePart1 = $this->dir.'/data_part_1.json';
        $this->assertFileNotExists($filePart1);

        $jsonBuffer->addData($dummyData);
        $this->assertFileExists($filePart1);

        $jsonBuffer->addData($dummyData);
        $filePart2 = $this->dir.'/data_part_2.json';
        $this->assertFileNotExists($filePart2);

        $jsonBuffer = null;
        $this->assertFileExists($filePart2);

        $jsonBuffer = $this->getCreatedJsonBuffer();
        $keepFilePath = $this->dir.'/do-not-delete.json';
        file_put_contents($keepFilePath, '[]');
        $jsonBuffer->removeDataFiles();
        $this->assertFileNotExists($filePart1);
        $this->assertFileNotExists($filePart2);
        $this->assertFileExists($keepFilePath);
    }

    /**
     * @return OutputBufferJsonFile
     */
    private function getCreatedJsonBuffer()
    {
        $jsonBuffer = new OutputBufferJsonFile();
        $jsonBuffer->outputDir = $this->dir;
        $jsonBuffer->dataUnitsPerFile = 2;
        return $jsonBuffer;
    }



}