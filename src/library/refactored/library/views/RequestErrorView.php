<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/18/18
 * Time: 9:21 PM
 */

namespace Views;


use Shared\Constants;
use ZErrors\InvalidRequestException;

class RequestErrorView extends BaseView
{
    const ERROR_TITLES = [
        400 => "400 Bad Request",
        403 => "403 Forbidden",
        404 => "404 Page Not Found"
    ];

    protected $message;
    protected $trace;

    public function __construct(InvalidRequestException $error) {
        $this->setMessage($error->getMessage());
        $this->setStackTrace($error->formatStackTrace());
        $this->setTitle(self::ERROR_TITLES[http_response_code()]);
        $this->setTemplate('request-error');
    }

    protected function setTemplate($name) {
        $file = self::TEMPLATES_DIR . $name . '.php';
        if (!file_exists($file)) {
            error_log($file . ' not found.');
            die();
        }
        ob_start();
            include $file;
        $this->template = ob_get_clean();
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