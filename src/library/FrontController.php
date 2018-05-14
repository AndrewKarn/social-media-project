<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 4:50 PM
 */

namespace Front;
require __DIR__ . "/../../vendor/autoload.php";
use Debugging\DebuggingMethods as Debug;
use Utility\Common as Utility;
use User\UserController;
// use Home\HomeModel;

class FrontController implements FrontControllerInterface
{
    const DEFAULT_CONTROLLER = "\Home\HomeController";
    const DEFAULT_ACTION = "getHomePage";

    protected $controller = self::DEFAULT_CONTROLLER;
    protected $action = self::DEFAULT_ACTION;
    protected $params = array();
    protected $uri;

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

    /** Parses request uri and decides the controller and method to init
     *
     */
    protected function parseURI() {
        $uri = $_SERVER["REQUEST_URI"];
        $this->uri = $uri;

        $params = Utility::formatUriQueryString($uri);
        // only allow standard characters

        $uri = substr(preg_replace('/[^a-zA-Z0-9\/]/', "", $uri), 1);

        @list($controller, $action) = explode('/', $uri, 3);

        if (isset($controller) && !empty($controller)) {
            $this->setController($controller);
        }

        if (isset($action) && !empty($action)) {
            $this->setAction($action);
        }

        if (isset($params) && !empty($params)) {
            $this->setParams($params);
        }
    }

    public function setController($namespace = self::DEFAULT_CONTROLLER)
    {
        $namespace = ucfirst(strtolower($namespace));
        $controller = '\\' . $namespace . '\\' . $namespace . 'Controller';
        if (!class_exists($controller)) {
            throw new \InvalidArgumentException("The controller '" . $controller . "' does not exist");
        }
        $this->controller = $controller;
    }

    public function setAction($action = self::DEFAULT_ACTION)
    {
        $methods = get_class_methods($this->controller);
        if (isset($methods)) {
            if (in_array($action, $methods)) {
                $this->action = $action;
                return $this;
            }
        } else {
            throw new InvalidRequestException(
                "The action '" . $action . "' does not exist in " . $this->controller . ".");
        }
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function route($controller, $action, $params = array())
    {
        Debug::logRouteVars($controller, $action, $params, $this->uri);
        $class = new $controller;

        if (!empty($params)) {
            error_log("\nRouted with params\n");
            $class->$action($params);
        } else {
            error_log("\nRouted without params\n");
            $class->$action();
        }
    }
}