<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 10:33 PM
 */
namespace Controllers;
use ZErrors\InvalidRequestException;
use Home\HomeController as Home;
use Shared\Constants as Constants;
class Router
{
    protected $controller;
    protected $method;

    public function __construct()
    {
        $req = new Request();
        error_log($req->getPath());
        $this->parsePath($req->getPath());
        $this->exec($req);
    }

    private function getController() {
        return $this->controller;
    }

    public function route() {
        $controller = $this->getController();
        var_dump($controller);
    }

    private function setController($controller) {
        try {
            if (!class_exists($controller)) {
                error_log($controller);
                throw new InvalidRequestException("The controller '" . $controller . "' does not exist");
            }
        } catch (InvalidRequestException $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
        }
        $this->controller = $controller;
    }

    private function setMethod($method) {
        $methods = get_class_methods($this->controller);
        try {
            if (isset($methods)) {
                if (in_array($method, $methods)) {
                    $this->method = $method;
                    return $this;
                }
            } else {
                throw new InvalidRequestException(
                    "The action '" . $method . "' does not exist in " . $this->getController() . ".");
            }
        } catch (InvalidRequestException $e) {
            error_log($e->getMessage());
        }
        return $this;
    }

    private function getMethod() {
        return $this->method;
    }

    private function parsePath($path) {
        $instructions = Constants::REQUEST_PATHS[$path];
        $this->setController($instructions["controller"]);
        $this->setMethod($instructions["method"]);
    }

    private function exec($req) {
        $class = $this->getController();
        $method = $this->getMethod();
        error_log($method);
        $class = new $class();
        $req ? $class->$method($req) : $class->$method();
    }
}