<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 6:30 PM
 */

namespace MongoShared;
require __DIR__ . '/../../../vendor/autoload.php';
use MongoDB;

class BaseQueries
{
    /** Queries db by collection for objectId
     *
     * @param string $collection MongoDB collection
     * @param string $objectId to search for
     * @param array $projectionFields option projection parameters
     *
     * @return array|null|object
     */
    public static function findById($collection, $objectId, $projectionFields = array()) {
        $collection = MongoUtilities::getCollection($collection);

        $idQuery = [
            "_id" => new MongoDB\BSON\ObjectId($objectId)
        ];

        if (!empty($projectionFields)) {
            $projection = MongoUtilities::makeProjection($projectionFields);
            $result = $collection->findOne($idQuery, $projection);
        } else {
            $result = $collection->findOne($idQuery);
        }

        if (empty($result)) {
            throw new \InvalidArgumentException("No results for id: " . $objectId . " in " . $collection . ".");
        }

        return $result;
    }

    public static function checkToken($objectId, $token) {
        self::findById("users", $objectId);
    }
}