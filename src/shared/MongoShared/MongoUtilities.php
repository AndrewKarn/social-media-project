<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 4/12/18
 * Time: 3:58 PM
 */

namespace MongoShared;
require __DIR__ . '/../../../vendor/autoload.php';
use MongoDB;

class MongoUtilities
{
    public static function getCollection($collection) {
        $db = new MongoDB\Client("mongodb://localhost:27017");
        return $db->main->$collection;
    }

    public static function makeProjection(array $fields) {
        $fieldsToProject = array();
        foreach($fields as $field) {
            $fieldsToProject[$field] = 1;
        }
        return array('projection' => $fieldsToProject);
    }

    /** formats cursor results in a specific way.
     * @param object $cursor
     * @param int $options:
     * 0 => default, return is acknowledged
     * 1 => return mongoId string
     * 2 => return insertedCount
     *
     * @return mixed
     */
    public static function readInsertCursor($cursor, $options = 0) {
        switch ($options) {
            case 0:
                return $cursor->isAcknowledged();
                break;
            case 1:
                return $cursor->getInsertedId()->__toString();
                break;
            case 2:
                return $cursor->getInsertedCount();
                break;
            case 3:
                return $cursor->getInsertedId();
                break;
        };
        return 'Read failed';
    }
}