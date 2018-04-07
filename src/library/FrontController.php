<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 4:50 PM
 */

namespace Utilities;

class FrontController implements FrontControllerInterface
{
    const DEFAULT_CONTROLLER = "HomeController";
    const DEFAULT_ACTION = "getHomePage";

    protected $controller = self::DEFAULT_CONTROLLER;
    protected $action = self::DEFAULT_ACTION;
    protected $params = array();

    /**
     * FrontController constructor.
     * @param array $options
     *
     * allows defaults to be set regardless of uri
     */
    public function __construct($options = array()) {
        if (empty($options)) {
            $this->parseURI();
            $this->route($this->controller, $this->action, $this->params);
        } else {
            if (isset($options["controller"])) {
                $this->setController($options["controller"]);
            }

            if (isset($options["action"])) {
                $this->setAction($options["action"]);
            }

            if (isset($options["params"])) {
                $this->setParams($options["params"]);
            }
            $this->route($this->controller, $this->action, $this->params);
        }
    }

    protected function parseURI() {
        $uri = $_SERVER["REQUEST_URI"];
        // only allow standard characters
        $uri = preg_replace('/[^a-zA-Z0-9]//', "", $uri);

        @list($controller, $action, $params) = explode('/', $uri, 3);

        if (isset($controller)) {
            $this->setController($controller);
        }

        if (isset($action)) {
            $this->setAction($action);
        }

        if (isset($params)) {
            $this->setParams(explode('/', $params));
        }
    }

    public function setController($controller = self::DEFAULT_CONTROLLER)
    {
        $controller = ucfirst(strtolower($controller));
        if (!class_exists($controller)) {
            throw new \InvalidArgumentException("The controller '" . $controller . "' does not exist");
        }
        return $this;
    }

    public function setAction($action = self::DEFAULT_ACTION)
    {
        $methods = get_class_methods($this->controller);
        if (isset($methods)) {
            if (in_array($action, $methods)) {
                $this->setAction($action);
                return $this;
            }
        } else {
            throw new \InvalidArgumentException(
                "The action '" . $action . "' does not exist in " . $this->controller . ".");
        }
        return $this;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function route($controller, $action, $params = array())
    {
        $class = new $controller;
        if (!empty($params)) {
            $class->$action($params);
        } else {
            $class->$action();
        }
    }
}