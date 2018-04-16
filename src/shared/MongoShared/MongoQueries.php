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

class BaseQueries
{
    /** Queries db by collection for objectId
     *
     * @param string $collection MongoDB collection
     * @param string $objectId to search for
     * @param array $projection option projection parameters field => 1
     * @param bool $toArray option to convert to usable array
     *
     * @return array|null|object
     */
    public static function findById($collection, $objectId, $projectionFields = array(), $toArray = true) {
        $collection = MongoUtilities::getCollection($collection);

        $idQuery = [
            "_id" => new MongoDB\BSON\ObjectId($objectId)
        ];

        if (!empty($projectionFields)) {
            $projection = MongoUtilities::makeProjection($projectionFields);
            $cursor = $collection->findOne($idQuery, $projection);
        } else {
            $cursor = $collection->findOne($idQuery);
        }

        if (empty($cursor)) {
            throw new \InvalidArgumentException("No results for id: " . $objectId . " in " . $collection . ".");
        }

        // returns usable array
        if ($toArray) {
            return $cursor->toArray();
        }
        // returns cursor
        return $cursor;
    }

    public static function checkToken($objectId, $token) {
        self::findById("users", $objectId);
    }
}