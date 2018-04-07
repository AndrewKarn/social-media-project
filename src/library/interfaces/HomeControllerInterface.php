<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 6:19 PM
 */
namespace Home;

interface HomeControllerInterface {
    public function checkAuthenticatedUser();
    public function getHomePage($authenticatedUser);
}