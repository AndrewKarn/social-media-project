<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/7/18
 * Time: 11:38 AM
 */

namespace Home;


class HomeView
{
    public function __construct($authenticatedUser = false)
    {
        if ($authenticatedUser) {
            // return or redirect to authenticatedUsers homepage from model info
        } else {
            $this->showUnauthenticatedHomepage();
        }
    }

    public function showUnauthenticatedHomepage() {
        $html = file_get_contents('unauthenticated-homepage.html', FILE_USE_INCLUDE_PATH);
        echo $html;
    }

}