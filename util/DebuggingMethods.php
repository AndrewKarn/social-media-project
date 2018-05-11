<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/7/18
 * Time: 2:52 PM
 */

namespace Debugging;
require_once __DIR__ . '/../vendor/autoload.php';
use Utility\Common as Utilities;

class DebuggingMethods
{
    public static function logRouteVars($controller = '', $action = '', $params = [], $uri) {
        $message = '';
        $message .= date('h:i:s A') . "\n";
        $message .= "The request URI is: " . $uri . "\n";
        if (!empty($controller)) {
            $message .= 'The controller from this uri is: ' . $controller . "\n";
        } else {
            $message .= 'The controller from this uri is blank.' . "\n";
        }
        if (!empty($action)) {
            $message .= 'The action from this uri is: ' . $action . "\n";
        } else {
            $message .= 'The action from this uri is blank.' . "\n";
        }
        if (!empty($params)) {
           //  $params = Common::formatUriQueryString($params);
            $message .= 'The param(s) from this uri:' . "\n";
            $i = 0;
            foreach ($params as $key => $val) {
                $message .= 'Param ' . ++$i . ': ' . $key . '=>' . $val . "\n";
            }
        } else {
            $message .= 'The params from this uri are blank.' . "\n";
        }
        file_put_contents('/var/log/nginx/route-var.log', $message);
    }
}