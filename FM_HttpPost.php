<?php

Class FM_HttpPost {
	private static $m_pIstance;
	private function FM_HttpPost() {}
	public static function inst() {
		if (!self::$m_pIstance) {
			self::$m_pIstance = new FM_HttpPost();
		}
		return self::$m_pIstance;
	}
	function isSetPost($postkey) {
		return isset($_POST[$postkey]);
	}
	function isSetFiles($postkey) {
		$count = count($_FILES[$postkey]['size']);
		for ($index = 0 ; $index < $count ; $index++) { 
			if ($_FILES[$postkey]['size'][$index] == 0) {
				return false;
			}
		}
		return true;
	}
	function isSetFile($postkey) {
		return $_FILES[$postkey]['size'] == 0 ? false : true;
	}

	public function post(string $httpPostKey) {
		return $_POST[$httpPostKey];
	}
	public function files(string $httpPostKey) { // multi filse 
		return $_FILES[$httpPostKey];
	}
	public function file(string $httpPostKey) { // single file
		return $_FILES[$httpPostKey];
	}
	public function _FILES() {
		return $_FILES;
	}
}

?>