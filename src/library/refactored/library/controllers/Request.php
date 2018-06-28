<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 8:59 PM
 */
namespace Controllers;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Shared\Constants;
use Utility\HttpUtils;
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
    private $requestBody;
    private $httpMethod;
    private $authenticated;
    private $token;
    private $queryStr;
    //private $sessionId;

    public function __construct() {
        //$this->setSessionId();
        $this->setServer();
        $this->setQueryStr();
        $this->parseUri();
        $this->checkToken();
        $this->setPath();
        $this->setRequestBody();
        $this->setHttpMethod();
    }

    public function getPathInfo () {
        $pathInfo = [];
        $pathInfo['path'] = $this->getPath();
        $pathInfo['httpMethod'] = $this->getHttpMethod();
        return $pathInfo;
    }

    private function setRequestBody() {
        $raw = json_decode(file_get_contents('php://input', true), true);
        if (!is_null($raw)) {
            $this->requestBody = $this->sanitizeRequestBody($raw);
        }
    }

//    private function setSessionId() {
//        session_start();
//        if (isset($_SERVER['PHPSESSID']) && !empty($_SERVER['PHPSESSID'])) {
//            $this->sessionId = $_SERVER['PHPSESSID'];
//        } else {
//            session_create_id();
//
//        }
//    }

    /**
     * Iterates over requestBody elements and cleans them before processing
     * @param array $requestBody
     * @return array $requestBody - clean
     */
    private function sanitizeRequestBody(array $requestBody) {
        array_walk_recursive($requestBody, function (&$val) {
            $val = trim($val);
            $val = strip_tags($val);
            $val = preg_replace('/\(|\)|\>|\</', '', $val);
        });
        return $requestBody;
    }


    public function getRequestBody() {
        return $this->requestBody;
    }

    private function setHttpMethod() {
        $this->httpMethod = $_SERVER["REQUEST_METHOD"];
    }

    public function getHttpMethod() {
        return $this->httpMethod;
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

    private function setQueryStr() {
        $this->queryStr = $_SERVER["QUERY_STRING"];
    }

    public function getQueryParams() {
        $params = [];
        parse_str($this->queryStr, $params);
        return $params;
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

    private function setAuthenticated ($status) {
        $this->authenticated = $status;
    }

    public function isAuthenticated () {
        return $this->authenticated;
    }

    private function setToken ($token) {
        $this->token = $token;
    }

    public function getToken () {
        return $this->isAuthenticated() ? $this->token : 'There is no token available';
    }

    /**
     * If there is a token in the request, check the token, sets authentication status
     */
    private function checkToken() {
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            $jwt = $_SERVER["HTTP_AUTHORIZATION"];
            try {
                $decoded = JWT::decode($jwt, Key::JWT_SECRET, array('HS512'));
                if (!empty($decoded)) {
                    $this->setAuthenticated(Constants::VALID_TOKEN);
                    $this->setToken($decoded);
                    // TODO implement a method to add additional data in jwt
                    $newJWT = HttpUtils::generateJWT($decoded->dat);

                    // experimental
//                    $pos1 = strpos($newJWT, '.');
//                    $pos2 = strpos($newJWT, '.', $pos1 + 1);
//                    $headerPaylod = substr($newJWT, 0, $pos2);
//                    $signature = substr($newJWT, $pos2);

                    header('Authorization:' . $newJWT);
                }
            } catch (BeforeValidException $e) {
                error_log($e->getMessage());
                $this->setAuthenticated(Constants::NBF_TOKEN);

            } catch (ExpiredException $e) {
                error_log($e->getMessage());
                $this->setAuthenticated(Constants::EXP_TOKEN);

            } catch (SignatureInvalidException $e) {
                error_log($e->getMessage());
                $this->setAuthenticated(Constants::INVALID_TOKEN);
            } catch (\Exception $e) {
                error_log($e->getMessage());
                error_log($e->getTraceAsString());
                error_log('Error catch in checkToken');
                http_response_code(501);
            }
        } else {
            $this->setAuthenticated(false);
        }
    }
}