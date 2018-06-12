<?php
namespace DB;
use Controllers\Response;
use Views\GenericErrorView;
use Shared\Constants;
use Utility;

class Write extends Query
{
	public static function createUser(array $validData) {
	    $validData['isActivated'] = false;
	    $validData['dateCreated'] = Base::timestamp();
		$usersDB = self::getCollection('users');
		try {
            $response = $usersDB->insertOne($validData);
            // return mongoId string if successful
            return $response->getInsertedId()->__toString();
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

	public static function update($collection, array $query, array $update) {
	    // TODO self::getCollection($collection)
        $db = self::getCollection($collection);
        try {
            $result = $db->updateOne($query, $update);
            return $result->isAcknowledged();
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            $details = $e->getWriteResult();
            $details = $details->getWriteErrors();
            $detail = $details[0];
            $code = $detail->getCode();
            $message = $detail->getMessage();
            $view = new GenericErrorView($message . '<br>' . 'Code: ' . $code);
            $response = new Response();
            $response->buildResponse(['error' =>  $view->getTemplate()])->send();
        }

    }
}