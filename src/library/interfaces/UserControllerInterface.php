<?php
namespace User;

interface UserControllerInterface {
	public function register();
	public function sendVerificationEmail($mongoId);
	
}