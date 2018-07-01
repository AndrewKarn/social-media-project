<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 8:59 PM
 */
namespace Controllers;
use DB\Base;
use Shared\Constants;
use Utility\HttpUtils;
use Utility\Key;
use Mailgun\Mailgun;
use DB\Write;
use DB\Query;
use MongoDB\Exception\InvalidArgumentException;
use Views\EmailView;
use Views\GenericErrorView;
use Views\RegisterView;
use Views\NoResultView;
use Views\RequestErrorView;
use Views\SingleBoxView;
use ZErrors\InvalidFormException;
use ZErrors\InvalidRequestException;
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
                'lastLogin',
                'lockout'
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
        if (!isset($user['lockout']) || $user['lockout'] < time()) {
            // check if account is currently locked.
            if (password_verify($validated["password"], $user["password"])) {
                $jwt = HttpUtils::generateJWT(['email' => $user["email"], '_id' => $user["_id"]->__toString(), 'firstname' => $user["firstname"]]);

                $pos2 = strpos($jwt, '.', strpos($jwt, '.') + 1);
                $headerPayload = substr($jwt, 0, $pos2);
                $signature = substr($jwt, $pos2);

                //header('Authorization:' . $newJWT);
                // ! Set to not secure for testing, look into https later.
                setcookie("jwt_payload", $headerPayload, time() + (60 * 15), '/',Constants::DOMAIN, false);
                setcookie("jwt_sig", $signature, time() + (60 * 15), '/',Constants::DOMAIN, false, true);
                //header('Authorization: ' . $jwt);
                $resp = new Response();
                $resp->buildResponse(['message' => 'Congrats ' . $user["firstname"] . '. You successfully logged in.', 'loggedIn' => true])->send();
                Write::update('users', ['_id' => $user["_id"]], ['$set' => ['lastLogin' => Base::timestamp()]]);
                die();
            } else {
                // if password is not validated.
                if (isset($user["loginAttempts"])) {
                    $attempts = $user["loginAttempts"];
                    if ($attempts < 5) {
                        $attempts++;
                    } else {
                        $lockout = time() + 900; // 15 min
                        $resp = new Response();
                        $resp->buildResponse(['message' => 'Maximum login attempts. Account locked for 15 minutes.',
                            'lockout' => ['lockout' => $lockout, 'email' => $validated['email']]
                        ])->send();
                    }
                } else {
                    $attempts = 1;
                }

                if (isset($lockout)) {
                    Write::update('users', ['_id' => $user['_id']], ['$set' => ['lockout' => $lockout, 'loginAttempts' => 0]]);
                } else {
                    Write::update('users', ['_id' => $user['_id']], ['$set' => ['loginAttempts' => $attempts]]);
                    $resp = new Response();
                    $resp->buildResponse(['message' => 'Wrong username or password.'])->send();
                }
            }
        } else {
            $diff = $user['lockout'] - time();
            $min = date('i', $diff);
            $secs = date('s', $diff);
            $resp = new Response();
            $resp->buildResponse(['message' => 'Account locked for ' . $min . ' minutes and ' . $secs . ' seconds.',
                'lockout' => ['lockout' => $user['lockout'], 'email' => $validated['email']]
            ])->send();
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
        $mongoId = Write::createUser($validated);
        if (isset($mongoId)) {
            $link = Constants::WEB_ROOT . 'user/register/?actHash=' . $generatedKey;
            $this->sendActivationEmail($validated["email"], $link);
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
                            throw new InvalidRequestException('The field: ' . $field . ' is not valid at ' . $this->getRequest()->getPath());
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

    private function sendActivationEmail ($email, $link) {
        $data = [];
        $data['body'] =  '<p>Thank you for joining!</p>
<br><a href="' . $link . '">' . $link . '</a>
<br><p>Clink the link above to register your email!</p>';

        $view = new EmailView('activation', $data);
        $emailContent = $view->getTemplate();
        $subject = "Your account with Zoe's Social Media!";
        $mgClient = new Mailgun(Key::MAIL_GUN_API_KEY);
        $domain = "mg.zoes-social-media-project.com";

        $result = $mgClient->sendMessage("$domain",
            array('from'    => 'Zoe\'s Social Media <postmaster@mg.zoes-social-media-project.com>',
                'to'      => 'Zoe Robertson <' . $email .'>',
                'subject' => $subject,
                'html'    => $emailContent));

        if ($result) {
            $response = new Response();
            $response->buildResponse(['message' => 'Email Verification sent! Please check your email to finish activation.'])->send();
            die();
        }
    }

    /**
     * @var $request Request
     */
    public function getRegistration () {
        $request = $this->getRequest();
        $queryParams = $request->getQueryParams();
        if (!empty($queryParams)) {
            $hash = $queryParams["hash"];
            $this->getEmailValidationSuccess($hash);
        } else {
            $this->getRegistrationPage();
        }
    }

    private function getEmailValidationSuccess ($hash) {
        $acknowledgement = Write::update('users', ['actHash' => $hash], [
            '$set' => [
                'isActivated' => true
            ]
        ]);
        if ($acknowledgement) {
            $data = [];
            $data['title'] = 'Welcome!';
            $data['content'] = '<h1>Thanks for registering!</h1>' .
                '<p>Click <a href="' . Constants::WEB_ROOT . 'user/login">here</a> to login!</p>';
            $view = new SingleBoxView($data);
            $view->render();
            die();
        } else {
            $view = new GenericErrorView('Uh oh, something went wrong. Sorry!');
            $view->render();
        }
    }

    private function getRegistrationPage() {
        $view = new RegisterView();
        $view->render();
    }

    public function getLoginPage() {
        $data = [];
        $data["scripts"] = ['login'];
        $data["title"] = 'Login';
        $data["content"] = '<h1>Login Here</h1>
            <div class="form">
                <form id="js-login-form">
                    <div>
                        <input name="email" type="email" placeholder="user@example.com" required>
                        <input name="password" type="password" placeholder="password" required>
                        <button id="js-login-btn" class="btn btn-right" type="submit">Login</button>
                    </div>
                </form>
            </div>';
        $view = new SingleBoxView($data);
        $view->render();
    }
}