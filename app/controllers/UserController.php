<?php

namespace app\controllers;
use vendor\core\base\View;
use app\models\User;

class UserController extends AppController {

	public $layout = 'user';
	public function signupAction() {
		if (!empty($_POST)) {
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			$user = new User();
			$data = $_POST;
			$user->load($data);
			if (!$user->validate($data) || !$user->checkUnique()){
				$user->getErrors();
				$_SESSION['form_data'] = $data;
				redirect();
			}
			$user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
			if ($user->save('user')){
				$user->sendActivationEmail($user->attributes['email']);
				$_SESSION['success'] = "Success! A link has been sent to your email address.\n Please follow it to activate your account.";
				redirect();
			}
			else {
				$_SESSION['error'] = 'Please try again later.';
			}
			redirect();
		}
		View::setMeta('Registration');
	}

	public function loginAction() { // for login
		if(!empty($_POST)){
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			$user = new User();
			$res = $user->login();
			if($res == 1){
				$_SESSION['success'] = 'You have logged in successfully!';
				$_SESSION['user'] = $_POST['login'];
				redirect('/');
			} else if($res == -1){
				$_SESSION['error'] = 'Wrong login/password.';
			} else {
				$_SESSION['error'] = 'Please activate your account first.';
			}
			redirect();
		}
		View::setMeta('Login');
	}

	public function activateAction() { 
		if(isset($_GET['email'], $_GET['email_code']) === true) {
			$user = new User();
			$res = $user->activation();
			if($res == 1){
				$_SESSION['success'] = 'Your account has been activated!';
			} else if($res == 0){
				$_SESSION['error'] = 'Your account has already been activated.';
			} else if($res == -1){
				$_SESSION['error'] = 'Activation failed. Please contact our support.';
			}
			redirect('/user/login');
		}
		View::setMeta('Activation');
	}

	public function logoutAction() { 
		
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);
			$_SESSION['success'] = "You have logged out successfully.";
			
		}
		redirect('/');
	}

	public function emailForResetAction() { 
		View::setMeta('Reset Password');
	}

	public function resetPassAction() { 
		if (!empty($_POST)) {
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			$user = new User();
			$data = $_POST;
			$user->load($data);
			if (!$user->validateOnlyLogin($data['login']) || !$user->validateOnlyEmail($data['email'])){
				$user->getErrors();
				redirect();
			}
			else 
			{
				if (!$user->checkIfRegistered($data))
				{
					$_SESSION['error'] = 'This login/email is not registered. Please sign up.';
					redirect();
				}
				else{
					$user->attributes['email'] = $_POST['email'];
					$user->attributes['login'] = $user->getLogin($_POST['email']);
					$user->attributes['email_code'] = $user->getCode($_POST['email']);
					$user->sendResetLink($user->attributes['email']);
					$_SESSION['success'] = "Success! A link has been sent to your email address.\n Please follow it to reset your password.";
					redirect();
				}
			}
		}
		View::setMeta('Reset Password');
	}

	public function changePassAction() { 
		
		if(isset($_GET['email'], $_GET['email_code']) === true) {
			$user = new User();
			$curUser = $user->reset();
			if($curUser){
				$_SESSION['user'] = $curUser;
			}
			else{
				$_SESSION['error'] = 'Link failed. Please try again.';
				if (isset($_SESSION['user']))
					unset($_SESSION['user']);
				redirect('/user/emailforreset');
			}
		} 
		if (!empty($_POST))
		{
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			$user = new User();
			$data = $_POST;
			$user->load($data);
			if (!$user->validateOnlyPass($data['password'])){
				$user->getErrors();
				$_SESSION['form_data'] = $data;
				redirect();
			}
			$user->attributes['login'] = $_SESSION['user'];
			$user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
			if ($user->updatePass($user->attributes['password'], $user->attributes['login'])){
				$_SESSION['success'] = "Success! Password has been changed.";
				if (isset($_SESSION['user']))
					unset($_SESSION['user']);
				redirect('/user/login');
			}
			else {
				$_SESSION['error'] = 'Please try again later.';
			}
		}
		View::setMeta('New Password');
	}

	public function preferencesAction() { 
		View::setMeta('Preferences');
	}

	public function editLoginAction() { 
		if (!empty($_POST))
		{
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			if (empty($_POST['check_password'])){
				$_SESSION['error'] = 'please enter your password to apply changes';
				redirect();
			}
			$user = new User();
			$data = $_POST;
			$user->load($data);
			$user->attributes['login'] = $_SESSION['user'];
			if (!$user->checkPass()){
				$_SESSION['error'] = 'Confirmation password incorrect';
				redirect();
			}
			if (!empty($_POST['new_login']))
			{
				if (!$user->validateOnlyLogin($data['new_login']) || !$user->checkUniqueLogin($data['new_login'])){
					$user->getErrors();
					$_SESSION['form_data'] = $data;
					redirect();
				}
				$user->attributes['login'] = $data['new_login'];
				$user->changeLogin($_SESSION['user'], $data['new_login']);
				$_SESSION['user'] = $data['new_login'];
				$_SESSION['success'] = 'Your login has successfully been changed!';
			}
		}
		View::setMeta('Edit details');
	}

	public function editPasswordAction() { 
		
		if (!empty($_POST))
		{
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			if (empty($_POST['check_password'])){
				$_SESSION['error'] = 'please enter your password to apply changes';
				redirect();
			}
			$user = new User();
			$data = $_POST;
			$user->load($data);
			$user->attributes['login'] = $_SESSION['user'];
			if (!$user->checkPass()){
				$_SESSION['error'] = 'Confirmation password incorrect';
				redirect();
			}
			if (!empty($_POST['new_password']) || !empty($_POST['confirm_new_password']))
			{
				if (empty($_POST['new_password'])){
					$_SESSION['error'] = 'Please enter password before its confirmation!';
					redirect();
				}
				if (empty($_POST['confirm_new_password'])){
					$_SESSION['error'] = 'Please enter password confirmation!';
					redirect();
				}
				if ($_POST['new_password'] != $_POST['confirm_new_password']){
					$_SESSION['error'] = 'Passwords do not match!';
					redirect();
				}
				if (!$user->validateOnlyPass($data['new_password'])){
					$user->getErrors();
					$_SESSION['form_data'] = $data;
					redirect();
				}
				$user->attributes['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
				if ($user->updatePass($user->attributes['password'], $_SESSION['user'])){
					$_SESSION['success'] = "Success! Password has been changed.";
				}
			}	
		}
		View::setMeta('Edit details');
	}

	public function editEmailAction() { 
		$user = new User();
		$_SESSION['email'] = $user->findEmail($_SESSION['user']);
		$_SESSION['email'] = $_SESSION['email'][0]['email'];
		if (!empty($_POST))
		{
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			if (empty($_POST['check_password'])){
				$_SESSION['error'] = 'please enter your password to apply changes';
				redirect();
			}
			$data = $_POST;
			$user->load($data);
			$user->attributes['login'] = $_SESSION['user'];
			if (!$user->checkPass()){
				$_SESSION['error'] = 'Confirmation password incorrect';
				redirect();
			}
			if (!empty($_POST['new_email']))
			{
				if (!$user->validateOnlyEmail($data['new_email'])){
					$user->getErrors();
					$_SESSION['form_data'] = $data;
					redirect();
				}
				$user->attributes['email'] = $data['new_email'];
				$user->attributes['login'] = $_SESSION['user'];
				$user->changeEmail($_SESSION['user'], $user->attributes['login']);
				$_SESSION['email'] = $data['new_email'];
				$_SESSION['success'] = 'Your email has successfully been changed!';
			}
		}
		View::setMeta('Edit details');
	}

	public function editNotificationsAction() {

		if (!empty($_POST))
		{
			foreach ($_POST as &$post_value) {
				$post_value = htmlspecialchars($post_value);
			}
			if (empty($_POST['check_password'])){
				$_SESSION['error'] = 'please enter your password to apply changes';
				redirect();
			}
			$user = new User();
			$data = $_POST;
			$user->load($data);
			$user->attributes['login'] = $_SESSION['user'];
			if (!$user->checkPass()){
				$_SESSION['error'] = 'Confirmation password incorrect';
				redirect();
			}
			$user->attributes['login'] = $_SESSION['user'];
			$user->changeNotifications();
			$_SESSION['success'] = 'Your notification preferences have been changed!';
		}
		View::setMeta('Edit details');
	}
}
