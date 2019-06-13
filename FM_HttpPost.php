<?php

Class FM_HttpPost {
	private function FM_HttpPost() {}
	static function isSetPost($postkey) : bool {
		$result = isset($_POST[$postkey]);
		FM_Log(__METHOD__, 'Post key:'.$postkey.($result == true ? ' is set' : ' is not set'));
		return $result;
	}
	static function isSetFiles($postkey) : bool {
		$count = count($_FILES[$postkey]['size']);
		for ($index = 0 ; $index < $count ; $index++) { 
			if ($_FILES[$postkey]['size'][$index] == 0) {
				FM_Log(__METHOD__, 'Post key:'.$postkey.' Index:'.$index.' is not set');
				return false;
			}
		}
		FM_Log(__METHOD__, 'Post key:'.$postkey.' is set');
		return true;
	}
	static function isSetFile($postkey) : bool {
		$result = $_FILES[$postkey]['size'] == 0 ? false : true;
		FM_Log(__METHOD__, 'Post key:'.$postkey.($result == true ? ' is set' : ' is not set'));
		return $result;
	}

	static function post(string $httpPostKey) : string {
		return $_POST[$httpPostKey];
	}
	static function files(string $httpPostKey) : array { // multi filse 
		return $_FILES[$httpPostKey];
	}
	static function file(string $httpPostKey) : array { // single file
		return $_FILES[$httpPostKey];
	}
	static function _FILES() : array {
		return $_FILES;
	}
}

?>