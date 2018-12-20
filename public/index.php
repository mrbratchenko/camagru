<?php
	
	use vendor\core\Router;
	use vendor\core\ErrorHandler;
	$query = rtrim($_SERVER['QUERY_STRING'], '/');
	
	define('DEBUG', 0);
	define('WWW', __DIR__);
	define('CORE', dirname(__DIR__) . '/vendor/core');
	define('ROOT', dirname(__DIR__));
	define('LIBS', dirname(__DIR__) . '/vendor/libs/');
	define('APP', dirname(__DIR__) . '/app');
	define('CACHE', dirname(__DIR__) . '/tmp/cache');
	define('LAYOUT', 'default');
	
	require '../vendor/libs/functions.php';
	
	spl_autoload_register(function($class) {
		$file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
		if (is_file($file)) {
			require_once $file;
		}
	});

	new \vendor\core\App;

	Router::add('^$', ['controller' => 'Gallery', 'action' => 'index']);
	Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

	if(!isset($_SESSION['user'])){
		if ($query == 'gallery/myphotos' || substr($query, 0, 9) == 'user/edit'){
			$err = new ErrorHandler();
			$err->displayError(0,0,0,0,401);
		}
	}
	Router::dispatch($query);

?>