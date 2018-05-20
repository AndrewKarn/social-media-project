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
    protected $route;

    public function formatStackTrace() {
        $trace = $this->getTrace();
        $formatted = '';
        $i = 0;
        foreach ($trace as $detail) {
//            $argString = '';
//            $params = $detail["args"];
//            foreach ($params as $param => $val) {
//                if ($param instanceof Request) {
//                    $argString .= 'Request => {' . "\n";
//                    foreach ($param as $key => $value) {
//                        $argString .= $key . ':' . $value . "\n";
//                    }
//                    $argString = '}, ';
//                } elseif (is_array($param)) {
//                    foreach ($param as $key => $value) {
//                        $argString .= '[' . $key . ':' . $value . '], ';
//                    }
//                } else {
//                    $argString .= $param . '=' . $val . "\n";
//                }
//            }
            $formatted .= "#" . $i++ . "\n";
            $formatted .= "File: " . $detail["file"] . "\n";
            $formatted .= "Line: " . $detail["line"] . "\n";
            $formatted .= "Function: " . $detail["function"] . '(' . @$detail["args"] . ')' . "\n\n";
        }
        return $formatted;
    }
}