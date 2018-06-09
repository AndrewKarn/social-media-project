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
    private $body;
    private $footer;

    const DEFAULT_EMAIL_FOOTER = '<p style="font-style: italic;">This is a test footer</p>';

    public function __construct($type, array $data)
    {
        $this->setEmailHeader($type);
        $this->setEmailBody($data['body']);
        if (isset($data['footer'])) {
            $this->setEmailFooter($data['footer']);
        } else {
            $this->setEmailFooter();
        }
        $this->setTemplate('email-template');
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
    private function setEmailBody ($body) {
        $this->body = $body;
    }

    public function getEmailBody() {
        return $this->body;
    }

    private function setEmailFooter ($footer = self::DEFAULT_EMAIL_FOOTER) {
        $this->footer = $footer;
    }

    public function getEmailFooter() {
        return $this->footer;
    }
}