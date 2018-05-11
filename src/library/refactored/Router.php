<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 10:33 PM
 */
namespace Controllers;
use Home\HomeController;
class Router
{
    protected $request;
    protected $controller;

    public function __construct()
    {
        $this->setRequest(new Request());
        error_log(var_dump($this->request));
        $this->setController();

    }

    private function setRequest(Request $request) {
        $this->request = $request;
    }

    private function getRequest() {
        return $this->request;
    }

    private function getController() {
        return $this->controller;
    }

    public function route() {
        $controller = $this->getController();
        var_dump($controller);
    }

    private function setController() {
        $controller = ucfirst(strtolower($this->getRequest()->getHandler()));
        try {
            if (!class_exists($controller)) {
                error_log($controller);
                throw new \InvalidRequestException("The controller '" . $controller . "' does not exist");
            }
        } catch (\InvalidRequestException $e) {
            error_log($e->getMessage());
        }
        $this->controller = $controller;
    }

    private function setAction($action) {
        $methods = get_class_methods($this->controller);
        if (isset($methods)) {
            if (in_array($action, $methods)) {
                $this->action = $action;
                return $this;
            }
        } else {
            //throw new \InvalidRequestException()tException(
            //    "The action '" . $action . "' does not exist in " . $this->controller . ".");
        }
        return $this;
    }
}