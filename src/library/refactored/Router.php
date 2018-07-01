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
        error_log($req->isAuthenticated());
        $this->parsePath($req);
    }

    private function getController() {
        return $this->controller;
    }

    public function route() {
        $controller = $this->getController();
        var_dump($controller);
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
                    $class = new $controller($req);
                    $class->$method();
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
                $class = new $controller($req);
                $class->$method();
            /**
             * For unprotected post, put, delete requests
             */
            } elseif ($pathInstrcts["requestBody"] && !$pathInstrcts["protected"]) {
                $data = $req->getRequestBody();
                if (!empty($data)) {
                    $class = new $controller($req);
                    $class->$method();
                } else {
                    http_response_code(400);
                    throw new InvalidRequestException('A request body was expected but not received.');
                }
            /**
             * For unprotected get requests
             */
            } else {
                $class = new $controller($req);
                $class->$method();
            }
        } catch (InvalidRequestException $e) {
            $errorView = new RequestErrorView($e);
            $errorView->render();
        }
    }

}