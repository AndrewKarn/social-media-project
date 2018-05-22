<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/21/18
 * Time: 7:30 PM
 */

namespace Views;
use ZErrors\NoResultException;

class NoResultView extends BaseView
{

    protected $message;
    protected $trace;

    public function __construct(NoResultException $error) {
        $this->setMessage($error->getMessage());
        $this->setStackTrace($error->formatStackTrace());
        $this->setTitle('No Results');
        $this->setTemplate('error-page');
    }

    protected function setMessage($message) {
        $this->message = $message;
    }

    protected function getMessage() {
        return $this->message;
    }

    protected function setStackTrace($trace) {
        $this->trace = $trace;
    }

    protected function getStackTrace() {
        return $this->trace;
    }
}