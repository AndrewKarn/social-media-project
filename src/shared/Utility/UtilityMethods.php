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
    public const SANITIZE_WHITESPACE = 'cleanWSpaces';

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
}