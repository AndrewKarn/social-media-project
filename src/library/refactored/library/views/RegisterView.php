<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 6/15/18
 * Time: 2:50 PM
 */

namespace Views;


class RegisterView extends BaseView
{
    public function __construct()
    {
        $this->setTitle('Register!');
        $this->setTemplate('register-page');
    }
}