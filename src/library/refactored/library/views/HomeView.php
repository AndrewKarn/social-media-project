<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/19/18
 * Time: 12:51 PM
 */

namespace Views;


class HomeView extends BaseView
{
    const HOME_STYLES = ['homepage', 'tingle.min'];

    public function __construct() {
        $this->setTitle('Home');
        $this->setStyles(self::HOME_STYLES);
        $this->setTemplate('homepage');
    }
}