<?php
namespace User;

interface UserControllerInterface {
	public function createUser(array $registrationFields);
	public function sendVerificationEmail($mongoId);
	
}