<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 4/12/18
 * Time: 3:58 PM
 */

namespace MongoShared;
use MongoDB;

class MongoUtilities
{
    public static function getCollection($collection) {
        $db = new MongoDB\Client("mongodb://localhost:27017");
        return $db->main->collection;
    }

    public static function makeProjection(array $fields) {
        $fieldsToProject = array();
        foreach($fields as $field) {
            $fieldsToProject[$field] = 1;
        }
        return array('projection' => $fieldsToProject);
    }
}