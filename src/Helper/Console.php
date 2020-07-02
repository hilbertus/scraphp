<?php


namespace Scraphp\Helper;


class Console
{
    public static function inputRequest(string $question): string
    {
        echo $question;
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        return trim($line);
    }
}