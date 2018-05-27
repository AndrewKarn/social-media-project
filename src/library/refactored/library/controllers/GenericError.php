<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/26/18
 * Time: 4:13 PM
 */

namespace Controllers;


class GenericError
{


    public function __construct($message)
    {
        $this->addErrorMessage($message);
    }

}