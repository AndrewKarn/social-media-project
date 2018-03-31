<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 3/30/18
 * Time: 4:15 PM
 */
require('vendor/autoload.php');
function createDBInstance($dbName) {
    $db = new MongoDB\Client("mongodb://localhost:27017");
    return $db->selectDatabase($dbName);
}
$db = createDBInstance('testDB');
$db->createCollection('test');