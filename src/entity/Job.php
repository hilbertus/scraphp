<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 03.04.2016
 * Time: 18:35
 */

namespace scraphp\Entity;


class Job
{
    /**
     * @var string
     */
    public $url;
    /**
     * @var string Path to script that should be executed
     */
    public $script;

    /**
     * @param string $url
     * @param string $script
     * @return Job
     */
    public static function create($url, $script)
    {
        $job = new Job();
        $job->url = $url;
        $job->script = $script;
        return $job;
    }
}