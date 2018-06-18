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
    const NO_RESULT_STYLES = ['std-error'];

    protected $message;
    protected $trace;

    public function __construct(NoResultException $error) {
        $this->setMessage($error->getMessage());
        $this->setStackTrace($error->formatStackTrace());
        $this->setTitle('No Results');
        $this->setStyles(self::NO_RESULT_STYLES);
        $this->setTemplate('exception-page');
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