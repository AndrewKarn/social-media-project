<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 10:33 PM
 */
namespace Controllers;
use Views\RequestErrorView;
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
        $this->parsePath($req);
        //$this->exec($req);
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

    private function parsePath(Request $req) {
        try {
            $instructions = Constants::REQUEST_PATHS[$req->getPath()];
            if (!isset($instructions)) {
                http_response_code(404);
                throw new InvalidRequestException('Bad path, no page found :(');
            }
            $pathInstrcts = $instructions["httpMethod"][$req->getHttpMethod()];
            if (!isset($pathInstrcts)) {
                http_response_code(404);
                throw new InvalidRequestException('Bad request, no ' . $req->getHttpMethod() . ' method defined yet.');
            }
            $controller = $instructions["controller"];
            $method = $pathInstrcts["method"];
            if (!isset($controller) || !isset($method)) {
                http_response_code(404);
                throw new InvalidRequestException('Bad request, no controller or method set.');
            }
        } catch (InvalidRequestException $e) {
            $errorView = new RequestErrorView($e);
            $errorView->render();
        }


        try {
            /**
             * For protected post, put, delete requests
             */
            if ($pathInstrcts["requestBody"] && $pathInstrcts["protected"]) {
                if ($req->isAuthenticated() !== 1) {
                    http_response_code(403);
                    throw new InvalidRequestException('A protected resource
                    was requested from an unauthenticated client.');
                }
                $data = $req->getRequestBody();
                if (!empty($data)) {
                    $class = new $controller();
                    $class->$method($data);
                } else {
                    http_response_code(400);
                    throw new InvalidRequestException('A request body was expected but not received.');
                }
            /**
             * For protected get requests
             */
            } elseif (!$pathInstrcts["requestBody"] && $pathInstrcts["protected"]) {
                if ($req->isAuthenticated() !== 1) {
                    http_response_code(403);
                    throw new InvalidRequestException('A protected resource
                    was requested from an unauthenticated client.');
                }
                $class = new $controller();
                $class->$method();
            /**
             * For unprotected post, put, delete requests
             */
            } elseif ($pathInstrcts["requestBody"] && !$pathInstrcts["protected"]) {
                $data = $req->getRequestBody();
                if (!empty($data)) {
                    $class = new $controller();
                    $class->$method($data);
                } else {
                    http_response_code(400);
                    throw new InvalidRequestException('A request body was expected but not received.');
                }
            /**
             * For unprotected get requests
             */
            } else {
                $class = new $controller();
                $class->$method();
            }
        } catch (InvalidRequestException $e) {
//            if ($req->isAjax()) {
//                $response = new Response();
//                $response->buildResponse(['Error from Router::parsePath' => $e->getMessage()])->send();
//            } else {
                $errorView = new RequestErrorView($e);
                $errorView->render();
           // }

        } catch (\Error $err) {
            http_response_code(500);
            $response = new Response();
            $response->buildResponse([$err->getTraceAsString(), $err->getMessage()])->send();
        }
    }

    private function exec($req) {
        $class = $this->getController();
        $method = $this->getMethod();
        error_log($method);
        $class = new $class();
        $req ? $class->$method($req) : $class->$method();
    }
}