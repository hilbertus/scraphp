<?php


namespace scraphp;


use scraphp\entity\Status;
use scraphp\interfaces\StatusUpdateInterface;

class StatusUpdateEcho implements StatusUpdateInterface
{

    /**
     * @param Status $status
     */
    public function updateStatus($status)
    {
        echo "\n";
        echo "url:\t\t" . $status->url . "\n";
        echo "script:\t\t" . $status->injectedScript . "\n";
        echo "seconds:\t" . number_format($status->elapsedSeconds, 4) . "\n";
        $percent = number_format(100 * $status->doneJobCount / ($status->doneJobCount + $status->remainingJobCount), 0);
        echo "jobs:\t\t" . $status->doneJobCount . " / " . ($status->doneJobCount + $status->remainingJobCount) . "\t" . $percent . "%";
        echo "\n";
    }
}