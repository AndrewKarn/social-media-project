<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/10/18
 * Time: 1:40 PM
 */

namespace Utility;


class HttpUtils
{
    const COOKIES = [
        'name' => 'UserName',
        'email' => 'UserEmail',
        'fromEmailVerification' => 'EmailVerificationLogin',
        'PHPSESSID' => 'SessionId'
    ];
    const WEB_ROOT = 'www.zoes-social-media-project.com/';
    /**
     *
     */
    public static function readAjax() {
        $data = json_decode(file_get_contents('php://input', true), true);

    }

    public static function redirect($url, $die = true, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        if ($die) {
            die();
        }
    }
    public static function revokeCookie($cookie, $kill = false) {
        setcookie($cookie, '',time() - 3600, '/', 'zoes-social-media-project.com');
        if ($kill) {
            die();
        }
    }

    public static function revokeAllCookies() {
        foreach(self::COOKIES as $cookie => $method) {
            self::revokeCookie($cookie);
        }
    }
}