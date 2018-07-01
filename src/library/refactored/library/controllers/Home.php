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
use Views\HomeView;
use Views\SingleBoxView;

class Home extends AbstractController
{
    public function getHomePage() {
        $view = new HomeView();
        $view->render();
    }

    public function getUserHomepage() {
        $req = $this->getRequest();
        $token = $req->getTokenData();
//        error_log("token:\n");
//        foreach ($token as $key => $val)
        $content['title'] = 'Hi' . $token['firstname'];
        $userDoc = Query::findById('users', $token['_id']);
        $content['content'] = (function ($userDoc) {
            ob_start();
                var_dump($userDoc);
            return ob_get_clean();
        })($userDoc);
        $view = new SingleBoxView($content);
        $view->render();
    }
}