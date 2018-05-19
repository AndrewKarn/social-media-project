<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/18/18
 * Time: 9:10 PM
 */

namespace Controllers;


class ErrorResponse extends Response
{
    private $errorCode;

    public function __construct() {
        $this->setErrorCode();
    }

    private function setErrorCode() {
        $this->errorCode = http_response_code();
    }

    private function getErrorCode() {
        return $this->errorCode;
    }

    private function makeErrorPage() {
        switch ($this->getErrorCode()) {
            case 400:

        }
    }
}