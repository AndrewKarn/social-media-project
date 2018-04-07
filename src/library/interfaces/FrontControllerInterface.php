<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 4:52 PM
 */
namespace Utilities;

interface FrontControllerInterface {
    public function setController($controller);
    public function setAction($action);
    public function setParams(array $params);
    public function route();
}