<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 6:30 PM
 */

namespace MongoShared;
require('../../../vendor/autoload.php');
use MongoDB;

class MongoQueries
{
    public static function getCollection($collection) {
        $db = new MongoDB\Client("mongodb://localhost:27017");
        return $db->main->$collection;
    }

    public static function findUserById($collection, $objectId, $projection = array(), $toArray = true) {
        $collection = self::getCollection($collection);
        $idQuery = [
            "_id" => new MongoDB\BSON\ObjectId($objectId)
        ];

        if (empty($projection)) {
            $cursor = $collection->findOne($idQuery);
        } else {
            $cursor = $collection->findOne(
                $idQuery,
                ['projection' => $projection]
            );
        }

        if (empty($cursor)) {
            throw new \InvalidArgumentException("No results for id: " . $objectId . ".");
        }

        // returns usable array
        if ($toArray) {
            return $cursor->toArray();
        }
        // returns cursor
        return $cursor;
    }

    public static function checkToken($objectId, $token) {
        self::findUserById("users", $objectId, [])
    }
}