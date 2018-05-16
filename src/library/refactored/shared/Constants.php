<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/13/18
 * Time: 6:06 PM
 */

namespace Shared;


class Constants
{
    const REQUEST_PATHS = [
        "home/default" => [
            "controller" => "Controllers\Home",
            "method" => "getHomePage"
        ],
        "user/default" => [
            "controller" => "Controllers\User",
            "method" => "login"
        ]
    ];
}