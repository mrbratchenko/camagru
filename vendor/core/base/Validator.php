<?php

namespace vendor\core\base;

use vendor\core\Db;

class Validator {

	public $errors = [
		'login' => [],
		'password' => [],
		'email' => [],
	];


	public function validate($data) {

		$this->validateLogin($data['login']);
		$this->validatePass($data['password']);
		$this->validateEmail($data['email']);

		if(!array_filter($this->errors)) {
			return true;		
		}

		return false;
	}

	public function validateOnlyLogin($login) {

		$this->validateLogin($login);

		if(!array_filter($this->errors)) {
			return true;		
		}

		return false;
	}

	public function validateOnlyPass($pass) {

		$this->validatePass($pass);

		if(!array_filter($this->errors)) {
			return true;		
		}

		return false;
	}

	public function validateOnlyEmail($email) {

		$this->validateEmail($email);

		if(!array_filter($this->errors)) {
			return true;		
		}

		return false;
	}

	public function validateLogin($str) {
		$i = 0;
		if (empty($str)){
			$this->errors['login'][$i] = 'Login is required';
			$i++;
		}
		if (strlen($str) < 3){
			$this->errors['login'][$i] = 'Login must be at least 3 characters long';
		}
	}

	public function validatePass($str) {
		$i = 0;
		if (empty($str)){
			$this->errors['password'][$i] = 'Password is required';
			$i++;
		}
		if (strlen($str) < 5){
			$this->errors['password'][$i] = 'Password is must be at least 5 characters long';
			$i++;
		}
		if (preg_match('#[A-Z]#', $str) === 0){
			$this->errors['password'][$i] = 'Password must contain at least one uppercase letter';
			$i++;
		}
		if (preg_match('#[a-z]#', $str) === 0){
			$this->errors['password'][$i] = 'Password must contain at least one lowercase letter';
			$i++;
		}
		if (preg_match('#[\W]+#', $str) === 0){
			$this->errors['password'][$i] = 'Password must contain at least one special character';
			$i++;
		}
	}

	public function validateEmail($str) {
		$i = 0;
		if (empty($str)){
			$this->errors['email'][$i] = 'Email is required';
			$i++;
		}
		if (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
			$this->errors['email'][$i] = 'Email is not valid';
			$i++;
		}
	}

}