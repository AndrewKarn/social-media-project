<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 8:59 PM
 */
namespace Controllers;
use User\UserController;
use Views\RequestErrorView;
use ZErrors\InvalidRequestException;
class User
{
    public function __construct()
    {

    }

    /**
     * @param array $reqBody - form data from login form
     */
    public function login(array $reqBody) {
        $validated = $this->validateLoginData($reqBody);
    }

    private function validateLoginData($loginData) {
        $validLoginData = array();
        try {
            if (isset($loginData['email']) && !empty($loginData['email'])) {
                $email = $loginData['email'];
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validLoginData['email'] = $email;
                } else {
                    throw new InvalidRequestException("There was an error, email:$email is not in valid email@test.com");
                }
            } else {
                throw new InvalidRequestException("There was an error, no email received by the server");
            }
            if (isset($loginData['password']) && !empty($loginData['password'])) {
                $password = $loginData['password'];
                if (preg_match('/^[\da-zA-Z!@$_.]{8,16}$/', $password)) {
                    $validLoginData['password'] = $password;
                } else {
                    throw new InvalidRequestException("There was an error, password: $password is not in a valid format");
                }
            }
        } catch (InvalidRequestException $e) {
            http_response_code(400);
            $view = new RequestErrorView($e);
            $response = new Response();
            error_log($response);
            $response->buildResponse(['error' => $view->getTemplate()])->send();
        }
        return $validLoginData;
    }
}