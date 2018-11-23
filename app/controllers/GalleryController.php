<?php

namespace app\controllers;

use app\models\Gallery;
use vendor\core\App;
use vendor\core\base\View;
use vendor\libs\Pagination;

class GalleryController extends AppController {

	public function indexAction() {
		$model = new Gallery;
		$total = $model->count();
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$perpage = 6;
		$pagination = new Pagination($page, $perpage, $total);
		$start = $pagination->getStart();
		$photos = $model->limit($start, $perpage);
		$user = 'guest';
		if (!empty($_SESSION['user'])){
			$user = $_SESSION['user'];
		}
		for($i = 0; $i < count($photos); $i++) {
			$photos[$i]['likes'] = $model->countLikes($photos[$i]['path']);	
			$photos[$i]['status'] = $model->userLike($user, $photos[$i]['path']);
			$photos[$i]['comments'] = $model->getComments($photos[$i]['path']);
			$photos[$i]['user_id'] = $model->getUserName($photos[$i]['user_id']);
		}
		$this->set(compact('photos', 'pagination', 'total', 'page'));
	}

	public function saveImgAction() {
		if($this->isAjax()) {
			$model = new Gallery;
			if (!empty($_SESSION)){
				$user = $_SESSION['user'];
			}
			$img = str_replace(' ', '+', $_POST['img']);
			$img = base64_decode($img);
			$file_name = $model->generateFileName($user);
			file_put_contents($file_name, $img);
			$model->saveImagePathToDb($user, $file_name);
			$total = $model->count();
			$page = (int)str_replace(' ', '+', $_POST['page']);
			if(!$page){
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			}
			$_GET['page'] = $page;
			$perpage = 6;
			if ($total > 1){
				$page = $total - 1;
			}
			$pagination = new Pagination($page, $perpage, $total);
			$start = $pagination->getStart();
			$photos = $model->limit($start, $perpage);
			for($i = 0; $i < count($photos); $i++) {
				$photos[$i]['likes'] = $model->countLikes($photos[$i]['path']);	
				$photos[$i]['status'] = $model->userLike($user, $photos[$i]['path']);
				$photos[$i]['comments'] = $model->getComments($photos[$i]['path']);
				$photos[$i]['user_id'] = $model->getUserName($photos[$i]['user_id']);
			}
			$this->loadView('index',compact('photos', 'pagination', 'total', 'page'));  
			exit;
		}	
	}

	public function deletePhotoAction() {
		$model = new Gallery;
		$user = 'guest';
		if (!empty($_SESSION)){
			$user = $_SESSION['user'];
		}
		$total = $model->count();
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$perpage = 6;
		$pagination = new Pagination($page, $perpage, $total);
		$start = $pagination->getStart();
		$likes = $model->findLikes();
		$path = str_replace(' ', '+', $_POST['path']);
		$model->deletePhoto($path);
		unlink($path);
		$photos = $model->limit($start, $perpage);		
		for($i = 0; $i < count($photos); $i++) {
			$photos[$i]['likes'] = $model->countLikes($photos[$i]['path']);
			$photos[$i]['status'] = $model->userLike($user, $photos[$i]['path']);
			$photos[$i]['comments'] = $model->getComments($photos[$i]['path']);
			$photos[$i]['user_id'] = $model->getUserName($photos[$i]['user_id']);
		}
		$this->loadView('myphotos', compact('photos', 'pagination', 'total', 'page'));  
		redirect();
	}

	public function likeAction() {
		if($this->isAjax()) {
			$model = new Gallery;
			if (!empty($_SESSION)){
				$user = $_SESSION['user'];
			}
			$path = str_replace(' ', '+', $_POST['path']);
			if(!$model->checkIfLikeExists($user, $path)){
				$model->addLike($user, $path);
				$model->sendLikeEmail($path, $user);
			}
			else{
				$model->changeLike($user, $path);
			}
			$likes = $model->countLikes($path);
			$status = $model->userLike($user, $path);
			echo $likes . '+' . $status;
			exit;
		}
	}

	public function addCommentAction() {
		if($this->isAjax()) {
			$model = new Gallery;
			if (!empty($_SESSION)){
				$user = $_SESSION['user'];
			}
			$path = str_replace(' ', '+', $_POST['path']);
			$com = htmlspecialchars($_POST['com']);
			$timestamp = $model->getTimestamp();
			$id = $model->addComment($user, $path, $com, $timestamp);
	       	$id = $id[0]['LAST_INSERT_ID()'];
			$array = array(
			    'user' => $user,
			    'timestamp' => $timestamp,
			    'comment' => $com,
			    'id' => $id
			);
			echo json_encode($array);
			exit;
		}
	}

	public function deleteCommentAction() {
		if($this->isAjax()) {
			$model = new Gallery;
			$id = str_replace(' ', '+', $_POST['id']);
			$model->deleteComment($id);
			exit;
		}
	}

	public function myphotosAction() {

		$this->layout = 'default';
		$model = new Gallery;
		$user = 'guest';
		if (!empty($_SESSION)){
			$user = $_SESSION['user'];
		}
		$total = $model->countMy($user);
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$perpage = 6;
		$pagination = new Pagination($page, $perpage, $total);
		$start = $pagination->getStart();
		$photos = $model->findAllMy($user);
		$photos = $model->limitMy($start, $perpage, $user);
		for($i = 0; $i < count($photos); $i++) {
			$photos[$i]['likes'] = $model->countLikes($photos[$i]['path']);	
			$photos[$i]['status'] = $model->userLike($user, $photos[$i]['path']);
			$photos[$i]['comments'] = $model->getComments($photos[$i]['path']);
			$photos[$i]['user_id'] = $model->getUserName($photos[$i]['user_id']);
		}
		$this->set(compact('photos', 'pagination', 'total', 'page'));
	}
}