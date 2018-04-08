<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/7/18
 * Time: 2:52 PM
 */

namespace Debugging;


class DebuggingMethods
{
    public static function logRouteVars($controller = '', $action = '', $params = '') {
        $message = '';
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
            $params = explode('&', $params);
            $message .= 'The param(s) from this uri:' . "\n";
            for ($i = 0; $i < count($params); $i++) {
                $message .= 'Param ' . ($i + 1) . ': ' . $params[$i] . "\n";
            }
        } else {
            $message .= 'The params from this uri are blank.' . "\n";
        }
        file_put_contents('/var/log/nginx/route-var.log', $message);
    }
}