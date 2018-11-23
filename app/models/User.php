<?php

namespace app\models;

use \vendor\core\base\Model;

class User extends Model {

	public $attributes = [
		'login' => '',
		'password' => '',
		'email' => '',
	];

	public $rules = [
		'required' => [
			['login'],
			['password'],
			['email'],
		],
		'email' => [
			['email'],
		],
		'lengMin' => [
			['password', 6],
		]
	];

	public function checkUniqueLogin($login){
		
		$user_login = $this->findBySql("SELECT login FROM user WHERE login=?", [$login]);
		if($user_login) {
			if ($user_login && $user_login[0]['login'] == $login) {
				$this->errors['unique'][] = 'Login already taken.';
			}
			return false;
		}
		return true;
	}

	public function checkUniqueEmail($email){
		
		$user_email = $this->findBySql("SELECT email FROM user WHERE email=?", [$email]);
		if($user_email) {
			if ($user_email && $user_email[0]['email'] == $email) {
				$this->errors['unique'][] = 'Email already taken.';
			}
			return false;
		}
		return true;
	}


	public function checkUnique(){
		
		$user_login = $this->findBySql("SELECT login FROM user WHERE login=?", [$this->attributes['login']]);
		$user_email = $this->findBySql("SELECT email FROM user WHERE email=?", [$this->attributes['email']]);

		if($user_login || $user_email) {
			if ($user_login && $user_login[0]['login'] == $this->attributes['login']) {
				$this->errors['unique'][] = 'Login already taken.';
			}
			if($user_email && $user_email[0]['email'] == $this->attributes['email']){
				$this->errors['unique'][] = 'Email already registered.';
			}
			return false;
		}
		return true;

	}

	public function login(){
		
		$login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null;
		$password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null;
		
		$activated = $this->findBySql("SELECT active FROM user WHERE login=?;", [$login]);

		if ($activated && $activated[0]['active'] == 0) {
			return 0;
		}

		if($login && $password){
			
			$user_login = $this->findBySql("SELECT login FROM user WHERE login=?", [$login]);
			$user_password = $this->findBySql("SELECT password FROM user WHERE login=?", [$login]);

			if($user_login && $user_password && $user_login[0]['login'] == $login && password_verify($password, $user_password[0]['password'])){
					return 1;
			}
		}
		return -1;
	}

	public function checkPass(){
		
		$login = !empty(trim($this->attributes['login'])) ? trim($this->attributes['login']) : null;
		$password = !empty(trim($_POST['check_password'])) ? trim($_POST['check_password']) : null;
		
		if($login && $password){
			
			$user_login = $this->findBySql("SELECT login FROM user WHERE login=?", [$login]);
			$user_password = $this->findBySql("SELECT password FROM user WHERE login=?", [$login]);

			if($user_login && $user_password && $user_login[0]['login'] == $login && 
				password_verify($password, $user_password[0]['password'])){
					return 1;
			}
		}
		return 0;
	}

	public function changeNotifications(){	
		$this->changeSql("UPDATE user SET notification_like=(?), notification_comment=(?) WHERE login=(?);", 
			[$_POST['choice_like'], $_POST['choice_comment'], $this->attributes['login']]);
	}

	public function activation(){
		
		$email = !empty(trim($_GET['email'])) ? trim($_GET['email']) : null;
		$email_code = !empty(trim($_GET['email_code'])) ? trim($_GET['email_code']) : null;

		$user_email = $this->findBySql("SELECT email FROM user WHERE email=?", [$email]);
		$user_email_code = $this->findBySql("SELECT email_code FROM user WHERE email_code=?", [$email_code]);
		$activated = $this->findBySql("SELECT active FROM user WHERE email=?;", [$email]);
		if ($activated && $activated[0]['active'] == 1) {
			return 0;
		}
		if($user_email && $user_email_code && $user_email[0]['email'] == $email && 
			$user_email_code[0]['email_code'] == $email_code) {
				$this->changeSql("UPDATE user SET active=1 WHERE email=?;", 
					[$user_email[0]['email']]);
				return 1;
			}
		return -1;
	}

	public function reset(){
		$email = !empty(trim($_GET['email'])) ? trim($_GET['email']) : null;
		$email_code = !empty(trim($_GET['email_code'])) ? trim($_GET['email_code']) : null;

		$user = $this->findBySql("SELECT login FROM user WHERE email=? AND email_code=? LIMIT 1", 
			[$email, $email_code]);
		return $user[0]['login'];
	}	

	public function changeLogin($old_login, $new_login){
		
		$this->changeSql("UPDATE user SET login=? WHERE login=?;", 
			[$new_login, $old_login]);
	}	

	public function updatePass($pass, $login) {
		$this->changeSql("UPDATE user SET password=(?) WHERE login=(?);", 
			[$pass, $login]);
		return true;
	}

	public function changeEmail() {
		$this->changeSql("UPDATE user SET email=(?) WHERE login=(?);", 
			[$this->attributes['email'], $this->attributes['login']]);
		return true;
	}

	public function sendActivationEmail($email){
		$to      = $email;
		$subject = 'Activate your camagru account';
		$message = 
		"Hello, " . $this->attributes['login'] . ", <br>You need to activate your account, please use the 
		link below:<br>http://localhost:8100/user/activate?email=" . $this->attributes['email'] . 
		"&email_code=" . $this->attributes['email_code'] . "<br> - camagru team.";
		$from_name = 'camagru';
		$from_mail = 'niceusername@ukr.net';

		$encoding = "utf-8";

		$subject_preferences = array(
			"input-charset" => $encoding,
			"output-charset" => $encoding,
			"line-length" => 76,
			"line-break-chars" => "\r\n"
		);

		$header = "Content-type: text/html; charset=".$encoding." \r\n";
		$header .= "From: ".$from_name." <".$from_mail."> \r\n";
		$header .= "MIME-Version: 1.0 \r\n";
		$header .= "Content-Transfer-Encoding: 8bit \r\n";
		$header .= "Date: ".date("r (T)")." \r\n";
		$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

		$result = mail($to, $subject, $message, $header);
	}

	public function sendResetLink($email){
		$to      = $email;
		$subject = 'A link to reset your password';
		$message = 
		"Hello, " . $this->attributes['login'] . ", <br>You have requested to reset your password. Please use the following link:<br>http://localhost:8100/user/changepass?email=" . $this->attributes['email'] . 
		"&email_code=" . $this->attributes['email_code'] . "<br> - camagru team.";
		$from_name = 'camagru';
		$from_mail = 'niceusername@ukr.net';

		$encoding = "utf-8";

		$subject_preferences = array(
			"input-charset" => $encoding,
			"output-charset" => $encoding,
			"line-length" => 76,
			"line-break-chars" => "\r\n"
		);

		$header = "Content-type: text/html; charset=".$encoding." \r\n";
		$header .= "From: ".$from_name." <".$from_mail."> \r\n";
		$header .= "MIME-Version: 1.0 \r\n";
		$header .= "Content-Transfer-Encoding: 8bit \r\n";
		$header .= "Date: ".date("r (T)")." \r\n";
		$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

		$result = mail($to, $subject, $message, $header);
	}



}