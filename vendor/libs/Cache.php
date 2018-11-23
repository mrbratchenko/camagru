<?php 

	namespace vendor\libs;

	class Cache {

		public function __construct() {

		}

		public function set($key, $data, $seconds = 3600) {
			$content['data'] = $data;
			$content['end_time'] = time() + $seconds;
			if (file_put_contents(CACHE . '/' . md5($key) . '.txt', serialize($content))) {
				return true;
			}
			return false;
		}
	}
 ?>