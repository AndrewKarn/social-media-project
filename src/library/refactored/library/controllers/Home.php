<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 8:59 PM
 */
namespace Controllers;
use Home\HomeController;
use Views\HomeView;

class Home extends AbstractController
{
    public function getHomePage() {
        $view = new HomeView();
        $view->render();
    }
}