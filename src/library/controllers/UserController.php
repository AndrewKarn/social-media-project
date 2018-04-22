<?php
namespace User;
use Debugging\DebuggingMethods;
use MongoShared\BaseQueries;
use MongoShared\MongoCreate;
use MongoShared\MongoUtilities;
use MongoShared\MongoUpdate;
use Utility\UtilityMethods;
use Mailgun\Mailgun;

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
        $document = BaseQueries::findById('users', $mongoId, ['email']);
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
        <h1>Thanks for signing up for www.zoes-social-media-project.com!</h1>
        <br>
        <p>
        Please click the link below to verify your email and activate your account.
        </p>
        <br>
        <a href='http://www.zoes-social-media-project.com/user/validate/?hash=" . $generatedKey . "'>
        <b>$generatedKey</b>
        </a>
        <br>
        <br>
        <span>-Zoe Robertson</span>
        <br>
        <span>www.zoes-social-media-project.com</span>
        ";

        $mgClient = new Mailgun(\Utility\Key::MAIL_GUN_API_KEY);
        $domain = "sandbox22f99ecb576b4667bac436b9c4603ff8.mailgun.org";


        $result = $mgClient->sendMessage("$domain",
            array('from'    => 'Zoe\'s Social Media <postmaster@sandbox22f99ecb576b4667bac436b9c4603ff8.mailgun.org>',
                'to'      => 'Zoe Robertson <' . $email .'>',
                'subject' => $subject,
                'html'    => $message));

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

    public function validate() {

	    UtilityMethods::redirect('http://www.zoes-social-media-project.com', false);
	    // cookie expires in 30 seconds from redirect
	    setcookie('fromEmailVerification', 'true', time() + 30, '/',
            'zoes-social-media-project.com', false, true);
	    die();
    }

}