<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 6:19 PM
 */
namespace Home;
require_once __DIR__ . '/../../../vendor/autoload.php';

class HomeController implements HomeControllerInterface {

    private $authenticatedUser;

    public function __construct()
    {
        $this->setAuthenticatedUser($this->checkAuthenticatedUser());
        if ($this->authenticatedUser) {
            // populate model and view
        } else {
            //
        }
    }

    private function setAuthenticatedUser($user) {
        $this->authenticatedUser = $user;
        return $this;
    }

    public function checkAuthenticatedUser()
    {
        session_start();
        return false;
    }

    public function getHomePage($authenticatedUser = false) {
        return new HomeView($authenticatedUser);
    }

}