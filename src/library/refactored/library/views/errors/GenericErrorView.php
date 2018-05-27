<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/26/18
 * Time: 4:11 PM
 */

namespace Views;


class GenericErrorView extends BaseView
{
    private $errors = [];

    public function __construct(string $message)
    {
        $this->addErrorMessage($message);
        $this->setTemplate('error');
        $this->setTitle('Error');
    }

    public function addErrorMessage (string $message) {
        $this->errors[] = $message;
    }

    public function getMessages() {
        return $this->errors;
    }
}