<?php
namespace User;
use Debugging\DebuggingMethods;
use MongoShared\MongoCreate;
use MongoShared\MongoUtilities;
use MongoShared\MongoUpdate;
use Utility\UtilityMethods;

require_once __DIR__ . '/../../../vendor/autoload.php';

class UserController implements UserControllerInterface {

	private $userMongoId;

	public function __construct() {

    }

    public function register() {
        $sanitizedFormData = UtilityMethods::sanitizePostData(UtilityMethods::SANITIZE_WHITESPACE);
        $validatedFormData = $this->validateRegistrationData($sanitizedFormData);
        $dbResponse = MongoCreate::createUser($validatedFormData);
        $mongoId = MongoUtilities::readInsertCursor($dbResponse, 1);
        $this->sendVerificationEmail($mongoId);
    }

    public function sendVerificationEmail($mongoId)
    {
        $document = \MongoShared\BaseQueries::findById('users', $mongoId, ['email']);
        $email = $document['email'];
        $generatedKey = sha1(mt_rand(10000,99999) . time() . $email);
        $inserted = MongoUpdate::insertOneField('users', ['_id'=> MongoUtilities::makeMongoId($mongoId)], 'verificationHash', $generatedKey);
        try {
            if (!$inserted) {
                throw new \MongoWriteConcernException("The database failed to write the account verification hash");
            }
        } catch (\MongoWriteConcernException $e) {
            error_log(json_encode($e));
        }

        $subject = "Your account with Zoe's Social Media!";

        $message = "
        Thanks for signing up for www.zoes-social-media-project.com!\n
        Please click the link below to verify your email and activate your account.\n
        \n
        http://www.zoes-social-media-project.com/user/validate/hash=" . $generatedKey . "\n
        \n
        -Zoe Robertson\n
        www.zoes-social-media-project.com
        ";

        $header = "From:noreply@zoessocialmediaproject.com\r\n";

        $result = mail($email, $subject, $message, $header);
        error_log(json_encode($result));
        if ($result) {
            return new AccountVerificationView($email);
        } else {
            return new AccountVerificationView();
        }
    }

    private function validateRegistrationData($registrationFields) {
		try {
	    	if (is_array($registrationFields)) {
	    		$cleanData = array();
	    		$check = 0;
	    		foreach ($registrationFields as $field => $val) {
	    			switch ($field) {
	    				case 'email':
	    					if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
	    						$cleanData['email'] = $val;
	    					} else {
	    						throw new \InvalidArgumentException ("This is not a valid email.");
	    					}
	    					break;
	    				case 'password':
	    				case 'passwordVerify':
	    					if (preg_match('/^[\da-zA-Z!@$_.]{8,16}$/', $val)) {
		    					$check++;
		    					if ($check === 1) {
		    						$firstPassword = $val;
		    					}
	    						if ($check === 2 && $firstPassword === $val) {
	    							$cleanData['password'] = $val;
	    						}
	    					} else {
	    						throw new \InvalidArgumentException ("This is not a valid password.");
	    					}
	    					break;
    					case 'firstname':
    					case 'lastname':
    						if (preg_match("/^[a-zA-Z']{2,25}$/", $val)) {
    							$cleanData[$field] = $val;
    						} else {
    							throw new \InvalidArgumentException ("This is not a valid name. Only normal characters and ' is allowed.");
    						}
    						break;
						case 'dob':
							if (preg_match('/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/', $val)) {
								$cleanData['dob'] = $val;
							} else {
								throw new \InvalidArgumentException ("This date is not in a valid format.");
							}
							break;
	    				default:
	    					# code...
	    					break;
	    			}
	    		}
	    		if (isset($cleanData['email']) && isset($cleanData['password']) && isset($cleanData['firstname'])
                    && isset($cleanData['lastname']) && isset($cleanData['dob'])) {
	    			    return $cleanData;
	    		}	
    		}
		} catch (\InvalidArgumentException $e) {

		}
		return $this;
    }

}