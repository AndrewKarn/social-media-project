<?php
namespace MongoShared;
require('../../../vendor/autoload.php');
use MongoDB;

class MongoCreate {
	public static function createUser(array $validData) {
		$usersDB = MongoUtilities::getCollection('users');
		$usersDB.
	}
}