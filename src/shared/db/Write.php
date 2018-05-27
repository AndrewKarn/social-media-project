<?php
namespace DB;
use Controllers\Response;
use Views\GenericErrorView;
use Shared\Constants;
use Utility;

class Write {
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
		        $view = new GenericErrorView('An account with ' . $email . ' already exists.<br>
Please click <a href="' . Constants::WEB_ROOT . 'home/default">here</a> to return home.');
		        $response = new Response();
		        $response->buildResponse(['error' => $view->getTemplate()])->send();
            }
        }
	}
}