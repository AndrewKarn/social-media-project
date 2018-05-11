<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 3/30/18
 * Time: 4:15 PM
 */
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Utility\Key;

$key = Key::JWT_SECRET;
$token = array(
    "iss" => "zoes-social-media-project.com",
    "aud" => "zoes-social-media-project.com",
    "iat" => time(),
    "nbf" => time() + 60,
    "data" => "This is test data."
);

$jwt = JWT::encode($token, $key, 'HS512');
var_dump($jwt);
echo "\n";
try {
    var_dump(JWT::decode($jwt, $key, array('HS512')));
} catch (\Firebase\JWT\BeforeValidException $e) {
    var_dump($e);
}

