<?php
namespace MongoShared;
require __DIR__ . '/../../../vendor/autoload.php';
use MongoDB;

class MongoCreate {
	public static function createUser(array $validData) {
		$usersDB = MongoUtilities::getCollection('users');
		$response = $usersDB->insertOne($validData);
		return $response;
	}
}