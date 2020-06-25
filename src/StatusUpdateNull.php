<?php

namespace scraphp;


use scraphp\Entity\Status;
use scraphp\Interfaces\StatusUpdateInterface;

class StatusUpdateNull implements StatusUpdateInterface
{

    /**
     * @param Status $status
     */
    public function updateStatus($status)
    {

    }
}