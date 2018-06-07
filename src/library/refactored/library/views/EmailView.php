<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 6/7/18
 * Time: 2:59 PM
 */

namespace Views;


class EmailView extends BaseView
{
    private $header;

    public function __construct($type, array $data)
    {
        $this->setTemplate('email-template');
        $this->setEmailHeader($type);
    }

    private function setEmailHeader($type) {
        switch ($type) {
            case 'activation':
                $this->header = '<h1>Thanks for joining Zoe\'s Social Media Project</h1>';
                break;
        }
    }

    public function getEmailHeader() {
        return $this->header;
    }

    // TODO implement get/setBodyCont, get/setEmailFooter
}