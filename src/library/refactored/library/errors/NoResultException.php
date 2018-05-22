<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/21/18
 * Time: 7:26 PM
 */

namespace ZErrors;


class NoResultException extends \Exception
{
    public function formatStackTrace() {
        $trace = $this->getTrace();
        $formatted = '';
        $i = 0;
        foreach ($trace as $detail) {
            $formatted .= "#" . $i++ . "\n";
            $formatted .= "File: " . $detail["file"] . "\n";
            $formatted .= "Line: " . $detail["line"] . "\n";
            $formatted .= "Function: " . $detail["function"] . '(' . @$detail["args"] . ')' . "\n\n";
        }
        return $formatted;
    }
}