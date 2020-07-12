<?php


namespace Scraphp\Helper;


class Parser
{

    public static function parseNumber(string $string, $decimalSeparator = '.'): float
    {
        $patter = "/[^\d\\" . $decimalSeparator . "]/";
        $cleanString = preg_replace($patter, '', $string);
        $englishFormat = str_replace($decimalSeparator, '.', $cleanString);
        return floatval($englishFormat);
    }
}