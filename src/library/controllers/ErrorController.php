<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 4/27/18
 * Time: 6:03 PM
 */

namespace Utility;

class ErrorController
{
    private $errorMessage;

    public function __construct(array $message)
    {
        $this->setErrorMessage($message);
        $this->checkError();
    }

    private function setErrorMessage($message) {
        $this->errorMessage = $message;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    private function checkError() {
        $error = $this->getErrorMessage();
        if (count($error) === 1) {
            $this->formatPublicSingleErrorPage();
        }
    }

    private function formatPublicSingleErrorPage() {
        $error = $this->getErrorMessage();
        $type = key($error);
        switch ($type) {
            case 'email':
                return new PublicErrorView($error);
                break;
            default:
                return new PublicErrorView(['default']);
        }
    }

//    private function formatPublicMultipleErrorPage() {
//        return true;
//    }
}