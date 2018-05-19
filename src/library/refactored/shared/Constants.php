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
            "httpMethod" => [
                "GET" => [
                    "method" => "getHomePage",
                    "requestBody" => false,
                    "protected" => false
                ]
            ]
        ],
        "user/default" => [
            "controller" => "Controllers\User",
            "httpMethod" => [
                "GET" => [

                ],
                "POST" => [
                    "method" => "login",
                    "requestBody" => true,
                    "protected" => false
                ]
            ]
        ]
    ];

    const VALID_TOKEN = 1;
    const NBF_TOKEN = 2;
    const EXP_TOKEN = 3;
    const INVALID_TOKEN = 0;

}