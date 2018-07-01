<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 8:59 PM
 */
namespace Controllers;
use DB\Query;
use Home\HomeController;
use Shared\Constants;
use Views\HomeView;
use Views\SingleBoxView;

class Home extends AbstractController
{
    public function getHomePage() {
        $req = $this->getRequest();
        $authenticated = $req->isAuthenticated();
        if ($authenticated === Constants::VALID_TOKEN) {
            $this->getUserHomepage($req);
        } else {
            $view = new HomeView();
            $view->render();
        }
    }

    public function getUserHomepage($req = null) {
        $req = $req ?? $this->getRequest();
        $token = $req->getTokenData();
        $content['title'] = 'Hi ' . $token['firstname'];
        $userDoc = Query::findById(
            'users',
            $token['_id'],
            ['lastname', 'dob', 'lastLogin']);
        // this will change
        $content['content'] = (function ($userDoc) {
            ob_start();
                var_dump($userDoc);
            return ob_get_clean();
        })($userDoc);
        $view = new SingleBoxView($content);
        $view->render();
    }
}