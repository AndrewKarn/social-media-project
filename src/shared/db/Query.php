<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/6/18
 * Time: 6:30 PM
 */

namespace DB;
require __DIR__ . '/../../../vendor/autoload.php';
use MongoDB;

class Query extends Base
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
        $collection = parent::getCollection($collection);

        $idQuery = [
            "_id" => new MongoDB\BSON\ObjectId($objectId)
        ];

        if (!empty($projectionFields)) {
            $projection = parent::project($projectionFields);
            $result = $collection->findOne($idQuery, $projection);
        } else {
            $result = $collection->findOne($idQuery);
        }

        if (empty($result)) {
            throw new \InvalidArgumentException("No results for id: " . $objectId . " in " . $collection . ".");
        }

        return $result;
    }

    public static function query($collection, $query, $projection = array()) {
        $collection = parent::getCollection($collection);

        if (!empty($projection)) {
            $projection = parent::project($projection);
            $result = $collection->findOne($query, $projection);
        } else {
            $result = $collection->findOne($query);
        }

        if (empty($result)) {
            throw new \InvalidArgumentException("No results for $query[0] in $collection");
        }

        return $result;
    }

}