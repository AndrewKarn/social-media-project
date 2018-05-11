<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 8:59 PM
 */
namespace Controllers;
use Firebase\JWT\JWT;
use Utility\Key;

class Request
{
    const DEFAULT_HANDLER = 'home';
    const DEFAULT_ACTION = 'default';

    private $server;
    private $uri;
    private $path;
    private $params;
    private $handler;
    private $action;

    public function __construct() {
        $this->setServer();
        $this->parseUri();
    }

    private function setServer() {
        $this->server = $_SERVER;
    }

    private function setUri() {
        $this->uri = $this->server["REQUEST_URI"];
    }

    public function getUri() {
        return $this->uri;
    }

    private function setHandler($handler) {
        $this->handler = $handler;
    }

    public function getHandler() {
        return $this->handler;
    }

    private function setAction($action) {
        $this->action = $action;
    }

    public function getAction() {
        return $this->action;
    }

    private function setParams($params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    private function setPath() {
        $this->path = $this->getHandler() . '/' . $this->getAction();
    }

    public function getPath() {
        return $this->path;
    }

    private function parseUri() {
        $this->setUri();
        $pre = substr($this->getUri(), 1);
        if (empty($pre)) {
             $this->setHandler(self::DEFAULT_HANDLER);
             $this->setAction(self::DEFAULT_ACTION);
             return 0; // used for tests
        }
        $sections = explode('/', $pre);
        $this->setHandler($sections[0]);
        if (empty($sections[1])) {
            $this->setAction(self::DEFAULT_ACTION);
            return 0;
        }
        $this->setAction($sections[1]);
        if (empty($sections[2])) {
            $this->setParams([]);
            return 0;
        }
        $this->setParams(array_slice($sections, 2));
    }

    private function checkToken() {
        $jwt = $_SERVER["HTTP_AUTHORIZATION"];
        JWT::decode($jwt, Key::JWT_SECRET, array('HS512'));
    }
}