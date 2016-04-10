<?php

namespace scraphp;


use scraphp\entity\Status;
use scraphp\interfaces\StatusUpdateInterface;

class StatusUpdateNull implements StatusUpdateInterface
{

    /**
     * @param Status $status
     */
    public function updateStatus($status)
    {

    }
}