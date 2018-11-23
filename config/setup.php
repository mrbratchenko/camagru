<?php


require 'database.php';

try {
	$DB_DSN = 'mysql:host=localhost;charset=utf8';
	$this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
	$this->pdo->query("
						CREATE DATABASE IF NOT EXISTS `camagru` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
						USE `camagru`;
						

						CREATE TABLE IF NOT EXISTS `user` 	(`id` 		int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
															`login` 	varchar(255) NOT NULL,
															`password` 	varchar(255) NOT NULL,
															`email_code`varchar(255) NOT NULL,
															`active` 	int(11) DEFAULT '0',
															`email` 	varchar(255) NOT NULL,
															`notification_like` int(11) DEFAULT '1',
  															`notification_comment` int(11) DEFAULT '1'
															);
						
						CREATE TABLE IF NOT EXISTS `photos` (`id` 		int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
															`user_id` 	int(11) UNSIGNED NOT NULL,
															`path` 		varchar(255) NOT NULL,
															FOREIGN KEY (user_id) REFERENCES user(id)
															);

						CREATE TABLE IF NOT EXISTS `likes` 	(`id` 		int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
															`user_id` 	int(11) UNSIGNED NOT NULL,
															`photo_id` 	int(11) UNSIGNED NOT NULL,
															`status` 	int(11) NOT NULL DEFAULT '1',
															FOREIGN KEY (user_id) REFERENCES user(id),
															FOREIGN KEY (photo_id) REFERENCES photos(id)
															);

						CREATE TABLE IF NOT EXISTS `comments` (`id` 	int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
															`user_id` 	int(11) UNSIGNED NOT NULL,
															`photo_id` 	int(11) UNSIGNED NOT NULL,
															`comment` 	text NOT NULL,
															`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
															FOREIGN KEY (user_id) REFERENCES user(id),
															FOREIGN KEY (photo_id) REFERENCES photos(id)
															);



						INSERT INTO `user` (`id`, `login`, `password`, `email_code`, `active`, `email`, `notification_like`, `notification_comment`) VALUES
									(1, 'test', '$2y$10$twSwpythZUnQ/Sy/JzGZwuWv8FUVmJ2TT8q02BXa5CNFNvykJa./a', 'febe3c5aa709ac3f5c638ccb78f18d10', 1, 'test@test.com', 1, 1);
						INSERT INTO `photos` (`id`, `user_id`, `path`) VALUES
															(1, 1, 'photos/test_4n6tx7yr3m.png'),
															(88, 1, 'photos/test_ujlyvqnab3.png'),
															(89, 1, 'photos/test_qmf6srd38q.png');
						INSERT INTO `likes` (`id`, `user_id`, `photo_id`, `status`) VALUES
															(1, 1, 1, 1),
															(4, 1, 88, 1),
															(5, 1, 89, 1);
						");
	if (isset($_SESSION['user'])){
		unset($_SESSION['user']);
	}
	$_SESSION['success'] = 'Database created successfully!';
	redirect('/');
	} 
	catch (PDOException $e) {
    	echo 'Connection failed: ' . $e->getMessage();
	}
