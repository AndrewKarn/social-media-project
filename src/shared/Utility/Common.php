<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/7/18
 * Time: 6:23 PM
 */

namespace Utility;


use Controllers\Request;

class Common
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
        $req = new Request();
        return array_map('self::' . $callback, $req->getRequestBody());
    }

    public static function makeAjaxModal($modalText) {
        return
            '<div class="modal-bkgd">
            <div class="alert-modal modal" id="js-alert-modal">
                <span id="js-x-alert-modal" class="x-modal">&times;</span>
                <span class="subheader bold">' . $modalText . '</span>
                <button id="js-close-alert-modal" type="button" class="close-modal-btn">Close</button>
            </div>
        </div>';
    }
}