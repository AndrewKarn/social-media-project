<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 6:19 PM
 */
namespace Home;
use Utility\Common;
use Utility\HttpUtils as Http;

require_once __DIR__ . '/../../../vendor/autoload.php';

class HomeController {

    private $authenticatedUser;
    private $emailVerificationLogin;
    private $sessionId;
    private $email;
    private $name;

    public function __construct()
    {
        $this->getCookies();
        // $this->checkAuthenticatedUser();
//        if ($this->authenticatedUser) {
//            // populate model and view
//        } else {
//            //
//        }
    }

    /** ---Gets cookies from request if they exist---
     * sets various initial variables to be used in the getHomePage method
     */
    private function getCookies() {
        foreach (Http::COOKIES as $cookie => $setter) {
            if (isset($_COOKIE[$cookie]) && !empty($_COOKIE[$cookie])) {
                $setter = 'set' . $setter;
                $this->$setter($_COOKIE[$cookie]);
            }
        }
//        if (isset($_COOKIE['fromEmailVerification']) && !empty($_COOKIE['fromEmailVerification'])) {
//            $this->setEmailVerificationLogin($_COOKIE['fromEmailVerification']);
//        }
//        if (isset($_COOKIE['PHPSESSID']) && !empty($_COOKIE['PHPSESSID'])) {
//            $this->setSessionId($_COOKIE['PHPSESSID']);
//        }
//        if (isset($_COOKIE['name']) && !empty($_COOKIE['name'])) {
//            $this->setUserName($_COOKIE['name']);
//        }
//        if (isset($_COOKIE['email']) && !empty($_COOKIE['email'])) {
//            $this->setUserEmail($_COOKIE['email']);
//        }
    }

    /** ---setter for auth token---
     * @param string  $userToken
     */
    private function setAuthenticatedUser($userToken) {
        $this->authenticatedUser = $userToken;
    }

    private function getAuthenticatedUser() {
        if (isset($this->authenticatedUser) && $this->authenticatedUser) {
            return $this->authenticatedUser;
        } else {
            return false;
        }
    }

    private function checkAuthenticatedUser()
    {

    }

    private function setEmailVerificationLogin($cookieVal) {
        $this->emailVerificationLogin = $cookieVal;
    }

    private function getEmailVerificationLogin() {
        if (!empty($this->emailVerificationLogin)) {
            return $this->emailVerificationLogin;
        } else {
            return false;
        }

    }

    private function setUserName($cookieVal) {
        $this->name = $cookieVal;
    }

    private function getUserName() {
        if (!empty($this->name)) {
            return $this->name;
        } else {
            return false;
        }
    }

    private function setUserEmail($cookieVal) {
        $this->email = $cookieVal;
    }

    private function getUserEmail() {
        if (!empty($this->email)) {
            return $this->name;
        } else {
            return false;
        }
    }

    private function setSessionId($cookieVal) {
        $this->sessionId = $cookieVal;
    }

    private function getSessionId() {
        return $this->sessionId;
    }

    /** ---Determines what version of homepage to return---
     * checks if request was made with cookie: fromEmailVerification=true
     *    -Returns HomeView with user's name and email ready for login
     * checks if request was made with a verified auth token
     *    -Redirects to protected user's homepage
     * otherwise returns default homepage
     *
     * @return HomeView
     */
    public function getHomePage() {
        if ($this->getEmailVerificationLogin()) {
            error_log("In homecontroller, routed through email verification");
            Http::revokeCookie('fromEmailVerification');
            Http::revokeCookie('name');
            //Common::revokeCookie('email');
            header("Cache-Control: no-cache, must-revalidate");
            header("Authorization: Bearer: jwt");
            return new HomeView('emailFwd', [
                'email' => $this->getUserEmail(),
                'name' => $this->getUserName()
            ]);
        }

        $authenticatedUser = $this->getAuthenticatedUser();
        if ($authenticatedUser) {
            return new HomeView($authenticatedUser);
        }

        error_log("got to 3rd condition first.");
        return new HomeView();
    }

}