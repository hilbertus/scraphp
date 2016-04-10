<?php

namespace scraphp\interfaces;

use scraphp\entity\Status;

interface StatusUpdateInterface
{
    /**
     * @param Status $status
     */
    public function updateStatus($status);
}