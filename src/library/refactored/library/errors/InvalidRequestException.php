<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 10:41 PM
 */
namespace ZErrors;
use Exception;
class InvalidRequestException extends Exception
{
    protected $route;
}