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
    public static function formatUriQueryString($uri) {
        parse_str(substr($uri, strpos($uri, '?') + 1), $results);
        return $results;
    }
}