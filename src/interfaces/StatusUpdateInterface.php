<?php

namespace scraphp\Interfaces;

use scraphp\Entity\Status;

interface StatusUpdateInterface
{
    /**
     * @param Status $status
     */
    public function updateStatus($status);
}