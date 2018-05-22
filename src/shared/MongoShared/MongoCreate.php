<?php
namespace MongoShared;
use Utility;
//require __DIR__ . '/../../../vendor/autoload.php';
//use MongoDB;

class MongoCreate {
	public static function createUser(array $validData) {
	    $validData['isActivated'] = false;
	    $validData['dateCreated'] = Base::timestamp();
		$usersDB = Base::getCollection('users');
		try {
            $response = $usersDB->insertOne($validData);
            return $response;
        } catch (\MongoDB\Driver\Exception\BulkWriteException $bulkWriteException) {
		    $exception = $bulkWriteException->getWriteResult();
		    $exception = $exception->getWriteErrors();
		    $exception = $exception[0];
		    $code = $exception->getCode();
		    // TODO implement a MongoUtility which checks error code.
		    if ($code == 11000) {
		        $email = $validData['email'];
		        return new Utility\ErrorController(['email' => $email]);
            }
        }
	}
}