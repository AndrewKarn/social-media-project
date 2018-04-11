<?php
namespace User;
require_once __DIR__ . '/../../../vendor/autoload.php';

interface UserControllerInterface {
	public function createUser(array $registrationFields);
	public function sendVerificationEmail($mongoId);
	
}