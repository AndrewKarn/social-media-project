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
    const GENERIC_ERR_STYLES = ['std-error'];

    private $errors = [];

    public function __construct(string $message)
    {
        $this->addErrorMessage($message);
        $this->setTitle('Error');
        $this->setStyles(self::GENERIC_ERR_STYLES);
        $this->setTemplate('error');
    }

    public function addErrorMessage (string $message) {
        $this->errors[] = $message;
    }

    public function getMessages() {
        return $this->errors;
    }
}