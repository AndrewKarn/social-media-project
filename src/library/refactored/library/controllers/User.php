<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 8:59 PM
 */
namespace Controllers;
use DB\Write;
use MongoDB\Exception\InvalidArgumentException;
use Utility\HttpUtils;
use Views\NoResultView;
use Views\RequestErrorView;
use ZErrors\InvalidFormException;
use ZErrors\InvalidRequestException;
use DB\Query;
use ZErrors\NoResultException;

class User extends AbstractController
{
    private $validationMessages = [
        "email" => 'Must be in valid user@email.com format.',
        "password" => 'Must be between 8 and 16 characters. Allowable special characters: ! @ $ _ .',
        "firstname" => 'Only alphabetical characters and \' allowed. Between 2 and 25.',
        "lastname" => 'Only alphabetical characters and \' allowed. Between 2 and 25.',
        "passwordVerify" => 'Must be between 8 and 16 characters. Allowable special characters: ! @ $ _ .'
    ];

    public function login()
    {
        $request = $this->getRequest();
        $validated = $this->validateLoginData($request->getRequestBody());

        try {
            $user = Query::query('users', ['email' => $validated['email']], [
                '_id',
                'email',
                'password',
                'firstname',
                'isActivated',
                'loginAttempts',
                'lastLogin'
            ]);
            if (empty($user)) {
                throw new NoResultException('There was no document found with email' . $validated["email"]);
            }
        } catch (InvalidArgumentException $e) {
            error_log($e->getMessage());
            die();
        } catch (NoResultException $ex) {
            $view = new NoResultView($ex);
            $view->render();
            die();
        }
        // TODO implement what to do after login.
        if (password_verify($validated["password"], $user["password"])) {
            $jwt = HttpUtils::generateJWT([$user["email"], $user["_id"]->__toString()]);
            header('Authorization: ' . $jwt);
            $resp = new Response();
            $resp->buildResponse(['Congrats ' . $user["firstname"] . '. You successfully logged in.'])->send();
            die();
        }
    }

    private function validateLoginData($loginData)
    {
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
            die();
        }
        return $validLoginData;
    }

    public function register()
    {
        $request = $this->getRequest();
        $validated = $this->validateRegistrationData($request->getRequestBody());
        $generatedKey = sha1(mt_rand(10000,99999) . time() . $validated["email"]);
        $validated["actHash"] = $generatedKey;
       // echo json_encode($validated);
        $mongoId = Write::createUser($validated);
       // echo json_encode($dbResult);
        if (isset($mongoId)) {
            $this->sendActivationEmail($validated["email"], $generatedKey);
        }
    }

    /** ---Validates registration post data fits db schema---
     * @param  array $registrationFields sanitized post data
     * @return array  $cleanData          validated post data
     * @throws \InvalidArgumentException  if any fields do not match
     */
    private function validateRegistrationData($registrationFields)
    {
        $caughtErrors = [];
        if (is_array($registrationFields)) {
            $cleanData = array();
            $check = 0;
            foreach ($registrationFields as $field => $val) {
                try {
                    switch ($field) {
                        case 'email':
                            if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
                                $cleanData['email'] = $val;
                            } else {
                                throw new InvalidFormException($field, $this->validationMessages[$field]);
                            }
                            break;
                        case 'password':
                        case 'passwordVerify':
                            if (preg_match('/^[\da-zA-Z!@$_.]{8,16}$/', $val)) {
                                $check++;
                                if ($check === 1) {
                                    $firstPassword = $val;
                                    continue;
                                }
                                if ($check === 2 && $firstPassword === $val) {
                                    $cleanData['password'] = password_hash($val, PASSWORD_DEFAULT);
                                } else {
                                    throw new InvalidFormException($field, 'Password fields do not match!');
                                }
                            } else {
                                throw new InvalidFormException($field, $this->validationMessages[$field]);
                            }
                            break;
                        case 'firstname':
                        case 'lastname':
                            if (preg_match("/^[a-zA-Z']{2,25}$/", $val)) {
                                $cleanData[$field] = $val;
                            } else {
                                throw new InvalidFormException($field, $this->validationMessages[$field]);
                            }
                            break;
                        case 'dob':
                            if (preg_match('/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/', $val)) {
                                $cleanData['dob'] = $val;
                            } else {
                                throw new InvalidRequestException("This date is not in a valid format.");
                            }
                            break;
                        default:
                            http_response_code(400);
                            throw new InvalidRequestException('The field: ' . $field . ' is not valid at ' . $request->getPath());
                            break;
                    }
                } catch (InvalidFormException $e) {
                    $caughtErrors[] = [$e->getField(), $e->getMessage()];
                } catch (InvalidRequestException $e) {
                    $res = new Response();
                    $res->buildResponse(['error' => $e->getMessage()])->send();
                    die();
                }
            }
            if (isset($cleanData['email']) && isset($cleanData['password']) && isset($cleanData['firstname'])
                && isset($cleanData['lastname']) && isset($cleanData['dob'])) {
               // $res = new Response();
               // $res->buildResponse(['congrats, you registered'])->send();
                return $cleanData;
            }
        }
        if (!empty($caughtErrors)) {
            $res = new Response();
            $res->buildResponse(["invalidForm" => $caughtErrors])->send();
            die();
        }
    }

    private function sendActivationEmail ($email, $hash) {

    }
}