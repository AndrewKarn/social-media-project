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
    const WEB_ROOT = 'http://www.zoes-social-media-project.com/';
    const REQUEST_PATHS = [
        "home/default" => [
            "controller" => "Controllers\Home",
            "httpMethod" => [
                "GET" => [
                    "method" => "getHomePage",
                    "requestBody" => false,
                    "protected" => false
                ]
            ]
        ],
        "user/login" => [
            "controller" => "Controllers\User",
            "httpMethod" => [
                "POST" => [
                    "method" => "login",
                    "requestBody" => true,
                    "protected" => false
                ]
            ]
        ],
        "user/registration" => [
            "controller" => "Controllers\User",
            "httpMethod" => [
                "POST" => [
                    "method" => "register",
                    "requestBody" => true,
                    "protected" => false
                ],
                "GET" => [
                    "method" => "getRegisSuccess",
                    "requestBody" => false,
                    "protected" => false
                ]
            ]
        ],
    ];

    const VALID_TOKEN = 1;
    const NBF_TOKEN = 2;
    const EXP_TOKEN = 3;
    const INVALID_TOKEN = 0;

}