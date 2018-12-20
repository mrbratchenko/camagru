<?php

namespace vendor\core\base;

use vendor\core\Db;

abstract class Model {

	protected $pdo;
	protected $table;
	protected $pk = 'id';
	public $attributes = [];
	public $errors = [];
	public $rules = [];

	public function __construct() {
		$this->pdo = Db::instance();
	}

	public function load($data) { 
		foreach ($this->attributes as $name => $value) {
			if (isset($data[$name])) {
				$this->attributes[$name] = $data[$name];
			}
		}
	}

	public function validate($data) {
		$v = new Validator();
		if($v->validate($data)){
			return true;
		}
		$this->errors = $v->errors;

		return false;
	}

	public function validateOnlyPass($pass) {
		$v = new Validator();
		if($v->validateOnlyPass($pass)){
			return true;
		}
		$this->errors = $v->errors;
		return false;
	}

	public function validateOnlyLogin($login) {
		$v = new Validator();
		if($v->validateOnlyLogin($login)){
			return true;
		}
		$this->errors = $v->errors;
		return false;
	}

	public function validateOnlyEmail($email) {
		$v = new Validator();
		if($v->validateOnlyEmail($email)){
			return true;
		}
		$this->errors = $v->errors;
		return false;
	}

	public function checkIfRegistered($data) {
		$sql = $this->pdo->query("SELECT * FROM user WHERE email=? AND login=? LIMIT 1;",
			[$data['email'], $data['login']]);
		if ($sql)
			return true;
		return false;
	}

	public function save($table) {
		$this->attributes['email_code'] = md5($this->attributes['login'].microtime());
		$tbl = $this->changeSql("INSERT INTO user(login, password, email, email_code) VALUES (?, ?, ?, ?);", 
			[$this->attributes['login'], $this->attributes['password'], $this->attributes['email'], $this->attributes['email_code']]);
		return true;
	}


	public function getErrors(){
		$errors = '<ul>';
		foreach ($this->errors as $err) {
			foreach ($err as $item) {
				$errors .= "<li>$item</li>";
			}
		}
		$errors .= '</ul>';
		$_SESSION['error'] = $errors;
	}

	public function query($sql) {
		return $this->pdo->execute($sql);
	}

	public function findAll() {
		$sql = "SELECT * FROM photos";
		return $this->pdo->query($sql);
	}

	public function findAllMy($user) {
		$sql = $this->findBySql("SELECT * FROM photos JOIN user ON photos.user_id=user.id WHERE login=?",
			[$user]);
		return $sql;
	}

	public function findLikes() {
		$sql = "SELECT * FROM likes WHERE status=1";
		return $this->pdo->query($sql);
	}

	public function findEmail($user) {
		$sql = $this->findBySql("SELECT email FROM user WHERE login=?",
			[$user]);
		return $sql;
	}

	public function findOne($id, $field = '') {
		$field = $field ?: $this->pk;
		$sql = "SELECT * FROM {$this->table} WHERE $field = ? LIMIT 1";
		return $this->pdo->query($sql, [$id]);
	}

	public function findBySql($sql, $params = []) {
		return $this->pdo->query($sql, $params);
	}

	public function changeSql($sql, $params = []) {
		return $this->pdo->execute($sql, $params);
	}

	public function generateFileName($user) {
		$path = 'photos/';
		$ext = '.png';
		$name = '';
		$len = 10;
	    $rand = array_merge(range(0, 9), range('a', 'z'));
	    for ($i = 0; $i < $len; $i++) {
	        $name .= $rand[array_rand($rand)];
	    }
	    return $path . $user . '_' .  $name . $ext;
	}

	public function saveImagePathToDb($user, $fileName) {
		$this->changeSql("INSERT INTO photos(user_id, path) VALUES ((SELECT id from user where login=?), ?);", 
			[$user, $fileName]);
	}

	public function count() {
		$sql = $this->findBySql("SELECT COUNT(*) FROM photos");
		return ($sql[0]['COUNT(*)']);
	}

	public function countMy($user) {
		$sql = $this->findBySql("SELECT COUNT(*) FROM photos JOIN user ON photos.user_id=user.id WHERE login=(?)",
			[$user]);
		
		return ($sql[0]['COUNT(*)']);
	}

	public function limit($start, $perpage) {
		$sql = "SELECT * FROM photos LIMIT $start, $perpage";
		return $this->pdo->query($sql);
	}

	public function limitMy($start, $perpage, $user) {
		$sql = $this->findBySql("SELECT * FROM photos JOIN user ON photos.user_id=user.id WHERE login=(?) LIMIT $start, $perpage",
			[$user]);
		return $sql;
	}

	public function checkIfLikeExists($user, $path) {
		$sql =  $this->findBySql("SELECT * FROM likes 
									JOIN user ON likes.user_id=user.id 
									JOIN photos ON likes.photo_id=photos.id
									WHERE login=? AND path=?", [$user, $path]);
		return $sql;
	}

	public function addLike($user, $path) {
		$this->changeSql("INSERT INTO likes(user_id, photo_id, status) VALUES ((SELECT id from user where login=?), 
			(SELECT id from photos where path=?), ?);", 
			[$user, $path, 1]);
	}

	public function changeLike($user, $path) {
		$newstatus = null;
		$status = $this->findBySql("SELECT status FROM likes 
									JOIN user ON likes.user_id=user.id 
									JOIN photos ON likes.photo_id=photos.id
									WHERE login=? 
									AND path=?",
					[$user, $path]);
		$status = $status[0]['status'];
		if ($status)
			$newstatus = 0;
		else
			$newstatus = 1;
		$this->changeSql("UPDATE likes SET status=(?) WHERE user_id=(SELECT id from user where login=?) 
						AND photo_id=(SELECT id from photos where path=?)", 
		[$newstatus, $user, $path]);
		return($newstatus);
	}

	public function countLikes($path) {
		$count = $this->findBySql("SELECT COUNT(*) FROM likes 
			JOIN photos ON likes.photo_id=photos.id 
			WHERE path=? AND status=(?)", [$path, 1]);
		return $count = $count[0]['COUNT(*)'];
	}

	public function deletePhoto($path) {
		$this->changeSql("DELETE FROM comments WHERE photo_id=(SELECT id from photos where path=?)", 
		[$path]);
		$this->changeSql("DELETE FROM likes WHERE photo_id=(SELECT id from photos where path=?)", 
		[$path]);
		$this->changeSql("DELETE FROM photos WHERE path=(?)", 
		[$path]);
	}

	public function userLike($user, $path) {
		$status = $this->findBySql("SELECT status FROM likes 
			JOIN photos ON likes.photo_id=photos.id 
			JOIN user ON likes.user_id=user.id
			WHERE path=? 
			AND login=? LIMIT 1", [$path, $user]);
		if (!$status)
			return ('post-like unliked');
		$status = $status[0]['status'];
		if ($status)
			return ('post-like liked');
		return ('post-like unliked');
	}

	public function getLogin($email) {
		$sql = $this->findBySql("SELECT login FROM user WHERE email=(?)",
			[$email]);
		return  $sql[0]['login'];	
	}

	public function getUserName($id) {
		$sql = $this->findBySql("SELECT login FROM user WHERE id=(?)",
			[$id]);
		return  $sql[0]['login'];	
	}

	public function getCode($email) {
		$sql = $this->findBySql("SELECT email_code FROM user WHERE email=(?)",
			[$email]);
		return $sql[0]['email_code'];	
	}

	public function getTimestamp() {
		
		date_default_timezone_set('Europe/Kiev');
		return date("Y-m-d H:i:s");
		
	}

	public function addComment($user, $photo, $comment, $timestamp) {
		
		$new = $this->changeSql("INSERT INTO comments (user_id, photo_id, comment, timestamp) 
						VALUES ((SELECT id from user where login=?),
						(SELECT id from photos where path=?) , ?, ?);", 
						[$user, $photo, $comment, $timestamp]);
		$this->sendCommentEmail($user, $photo, $comment);
		return $this->pdo->query('SELECT LAST_INSERT_ID()');
	}

	public function deleteComment($id) {

		$this->changeSql("DELETE FROM comments WHERE id=(?);", [$id]); 

	}

	public function getComments($photo) {
		$comments = $this->findBySql("SELECT id, user_id, comment, timestamp FROM comments 
			WHERE photo_id=(SELECT id from photos where path=?)", [$photo]);
		for($i = 0; $i < count($comments); $i++) {
			$user_id = $this->findBySql("SELECT login from user where id=?", [$comments[$i]['user_id']]);	
			$comments[$i]['user'] = $user_id[0]['login'];
		}
		

		return($comments);
	}
	

	public function sendLikeEmail($path, $user){

		$to      = $this->findBySql("SELECT login,email,notification_like from user WHERE id=(SELECT user_id FROM photos WHERE path=(?) LIMIT 1)", [$path]);
		if (!$to[0]['notification_like'] || $to[0]['login'] == $user)
			return ;
		$to_email = $to[0]['email'];
		$subject = 'Another user just liked your photo';
		$message = 
		"Hello, " . $to[0]['login'] . "<br>". $user . " just liked your photo! <br> - camagru team.";
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

		$result = mail($to_email, $subject, $message, $header);
	}

	public function sendCommentEmail($user, $path, $comment){

		$to      = $this->findBySql("SELECT login,email,notification_comment from user WHERE id=(SELECT user_id FROM photos WHERE path=(?) LIMIT 1)", [$path]);

		if (!$to[0]['notification_comment'] || $to[0]['login'] == $user)
			return ;
		$to_email = $to[0]['email'];
		$subject = 'Another user just commented your photo';
		$message = 
		"Hello, " . $to[0]['login'] . "<br>". $user . " just commented your photo:<br> \"" . $comment . "\"<br>" . " camagru team.";
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

		$result = mail($to_email, $subject, $message, $header);
	}
	
}