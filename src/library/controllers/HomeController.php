<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 6:19 PM
 */
namespace Home;
use Utility\UtilityMethods;

require_once __DIR__ . '/../../../vendor/autoload.php';

class HomeController {

    private $authenticatedUser;
    private $emailVerificationLogin;
    private $sessionId;

    public function __construct()
    {
        $this->checkCookies();
        // $this->checkAuthenticatedUser();
//        if ($this->authenticatedUser) {
//            // populate model and view
//        } else {
//            //
//        }
    }

    private function checkCookies() {
        if (isset($_COOKIE['fromEmailVerification']) && !empty($_COOKIE['fromEmailVerification'])) {
            $this->setEmailVerificationLogin($_COOKIE['fromEmailVerification']);
        }
        if (isset($_COOKIE['PHPSESSID']) && !empty($_COOKIE['PHPSESSID'])) {
            $this->setSessionId($_COOKIE['PHPSESSID']);
        }
    }

    private function setAuthenticatedUser($user) {
        $this->authenticatedUser = $user;
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
        session_start();
        return false;
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

    private function setSessionId($cookieVal) {
        $this->sessionId = $cookieVal;
    }

    private function getSessionId() {
        return $this->sessionId;
    }

    public function getHomePage() {
        if ($this->getEmailVerificationLogin()) {
            error_log("In homecontroller, routed through email verification");
            UtilityMethods::revokeCookie('fromEmailVerification');
            return new HomeView('emailFwd');
        }

        $authenticatedUser = $this->getAuthenticatedUser();
        if ($authenticatedUser) {
            return new HomeView($authenticatedUser);
        }
        error_log("got to 3rd condition first.");
        return new HomeView();
    }

}