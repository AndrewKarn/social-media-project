<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/24/18
 * Time: 7:18 PM
 */

namespace ZErrors;


use Throwable;

class InvalidFormException extends \Error
{
    private $field;

    public function __construct(string $field, string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setField($field);
    }

    public function getField() {
        return $this->field;
    }

    private function setField($field) {
        $this->field = $field;
    }
}