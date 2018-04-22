<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/7/18
 * Time: 6:23 PM
 */

namespace Utility;


class UtilityMethods
{
    const SANITIZE_WHITESPACE = 'cleanWSpaces';

    public static function formatUriQueryString($uri) {
        parse_str(substr($uri, strpos($uri, '?') + 1), $results);
        return $results;
    }

    public static function cleanWSpaces($val) {
        $val = trim($val);
        $val = strip_tags($val);
        return preg_replace('/\(|\)|\>|\</', '', $val);
    }

    public static function sanitizePostData($callback) {
        return array_map('self::' . $callback, $_POST);
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
}