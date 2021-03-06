<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 10:41 PM
 */
namespace ZErrors;
use Controllers\Request;
use Controllers\Response;
use Exception;
class InvalidRequestException extends Exception
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