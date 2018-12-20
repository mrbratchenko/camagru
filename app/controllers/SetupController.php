<?php

namespace app\controllers;

class SetupController extends AppController {

	public function indexAction() {
		require ROOT . '/config/setup.php';
		die;
	}
}