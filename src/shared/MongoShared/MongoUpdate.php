<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 4/18/18
 * Time: 5:49 PM
 */

namespace MongoShared;
require __DIR__ . '/../../../vendor/autoload.php';
use MongoDB;

class MongoUpdate
{
    public static function insertOneField($collection, $query, $field, $val) {
        $collection = MongoUtilities::getCollection($collection);
        $success = $collection->updateOne($query, ['$set' => [$field => $val]])->isAcknowledged();
        return $success;
    }
    public static function insertManyFields($collection, $query, array $fields) {
        $collection = MongoUtilities::getCollection($collection);
        $success = $collection->updateMany($query, ['$set' => $fields])->isAcknowledged();
        return $success;
    }
}