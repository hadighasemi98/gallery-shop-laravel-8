<?php

namespace App\Utilities;

use App\Utilities\Helpers\Contracts\HelperInterface;

class Helper implements HelperInterface
{

    /**
     * > It takes a string and returns it in the snake_case format
     * 
     * @param String input The string to be converted.
     * 
     * @return String A string in snake case.
     */
    public static function serializeToSnackCase(String $input): String
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('_', $ret);
    }
}
