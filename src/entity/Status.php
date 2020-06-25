<?php
/**
 * User: Alexander
 * Date: 10.04.2016 17:58
 */

namespace scraphp\Entity;


class Status
{
    /**
     * @var string
     */
    public $url;
    /**
     * @var string
     */
    public $injectedScript;
    /**
     * @var string
     */
    public $elapsedSeconds;
    /**
     * @var int
     */
    public $doneJobCount;
    /**
     * @var int
     */
    public $remainingJobCount;
}