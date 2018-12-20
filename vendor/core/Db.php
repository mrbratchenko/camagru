<?php

namespace vendor\core;

use PDO;

class Db {
	use TSingletone;
	protected $pdo;

	public static $countSql = 0;
	public static $queries = [];

	protected function __construct(){

		try {
			require ROOT . '/config/database.php';
			$this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		} 
		catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}		
	}

	public function execute($sql, $params = []) { 
		self::$countSql++;
		self::$queries[] = $sql;
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute($params);
	}

	public function query($sql, $params = []) { 
		self::$countSql++;
		self::$queries[] = $sql;
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute($params);
		if ($res !== false){
			return $stmt->fetchAll();
		}
		return [];
	}
}