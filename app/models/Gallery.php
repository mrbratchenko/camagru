<?php

namespace app\models;
use vendor\libs\Pagination;

class Gallery extends \vendor\core\base\Model {

	public $table = 'user';
	public $pk = 'id';

	public function updatePhotos($photos){

		if (!empty($_SESSION)){
			$user = $_SESSION['user'];
		}

		for($i = 0; $i < count($photos); $i++) {
			$photos[$i]['likes'] = $this->countLikes($photos[$i]['path']);	
			$photos[$i]['status'] = $this->userLike($user, $photos[$i]['path']);
			$photos[$i]['comments'] = $this->getComments($photos[$i]['path']);
		}
		return $photos;
	}

}