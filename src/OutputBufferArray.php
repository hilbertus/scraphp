<?php


namespace scraphp;

use scraphp\interfaces\OutputBufferInterface;

class OutputBufferArray implements OutputBufferInterface
{

    protected $data = [];

    public function addData($array)
    {
        $this->data[] = $array;
    }

    public function getData()
    {
        return $this->data;
    }
}