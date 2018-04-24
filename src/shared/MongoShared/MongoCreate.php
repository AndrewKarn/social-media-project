<?php
namespace MongoShared;
//require __DIR__ . '/../../../vendor/autoload.php';
//use MongoDB;

class MongoCreate {
	public static function createUser(array $validData) {
	    $validData['isActivated'] = false;
	    $validData['dateCreated'] = MongoUtilities::timestamp();
		$usersDB = MongoUtilities::getCollection('users');
		$response = $usersDB->insertOne($validData);
		return $response;
	}
}