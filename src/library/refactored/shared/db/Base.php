<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 4/12/18
 * Time: 3:58 PM
 */

namespace DB;
//require __DIR__ . '/../../../vendor/autoload.php';
use MongoDB;

class Base
{
    public static function getCollection($collection) {
        $db = new MongoDB\Client("mongodb://192.168.50.40:27017", [], [
            'typeMap' => [
                'array' => 'array',
                'document' => 'array',
                'root' => 'array'
            ]
        ]);
        return $db->main->$collection;
    }

    public static function project(array $fields) {
        $fieldsToProject = array();
        foreach($fields as $field) {
            $fieldsToProject[$field] = 1;
        }
        return ['projection' => $fieldsToProject];
    }

    /** creates timestamp for MongoDB
     * @return string
     */
    public static function timestamp() {
        $utcdate = new MongoDB\BSON\UTCDateTime();
        return $utcdate->toDateTime()->format('c');
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
        if (!$cursor) {
            return false;
        }
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

    public static function makeMongoId($mongoIdString) {
        return new MongoDB\BSON\ObjectId($mongoIdString);
    }
}