<?php

class FM_Files {
	public $postkey; // the key of http post
	public $postIds; // wordpress post id or attachment id
	private $rowFiles; // same as $_FILES[$this->postkey];
	private $allKeys; // name, type, tmp_name, error, size
	private $files;
	function FM_Files (string $postkey) {
		FM_Log(__METHOD__, 'Post key:'.$postkey);
		$this->postkey = $postkey;
		$this->rowFiles = FM_HttpPost::inst()->files($postkey);
		$this->allKeys = array_keys($this->rowFiles);
		$this->files = $this->getFiles();
		$this->postIds = array();
	}
	function media_handle_upload() {
		FM_Log(__METHOD__, 'Post key:'.$postkey.'Uploading');
		foreach ($this->files as $key => $file) {
			require_once_for_media_handle_upload();
			$_FILES = array_merge($_FILES, array('upload_file' => $file)); // Better use FM_HttpPost to get $_FILES
			$postId = media_handle_upload('upload_file', 0);
			array_pop($_FILES);
			if (is_wp_error($postId)) {
				FM_Log(__METHOD__, 'Post key:'.$this->postkey.' Cannot upload. File name:'.file['name']);
			} 
			else {
				FM_Log(__METHOD__, 'Post key:'.$this->postkey.' Uploaded. File name:'.$file['name'].' Post id:'.$postId);
				array_push($this->postIds, $postId);
			}
		}
	}
	private function count () {
		return count($this->rowFiles[$this->allKeys[0]]);
	}
	private function getFiles() {
		$files = array();
		for ($index = 0; $index < $this->count(); $index++) {
			$file = array();		
			foreach ($this->allKeys as $value) {
				$file = array_merge($file, array($value => $this->rowFiles[$value][$index]));	
			}
			array_push($files, $file);
		}
		return $files;
	}
	function isUploaded() {
		if (count($this->postIds) == 0) {
			FM_Log(__METHOD__, 'Post key:'.$this->postkey.'. Haven\'t call media_handle_upload yet.');
			return false;
		}
		return true;
	}
	public function __toString() {
		$string = '';
		$string .= '=========='.$this->postkey.'==========';
		foreach ($this->allKeys as $key) {
			$string .= $this->postkey.'['.$key.']'.':'.$this->rowFiles[$this->postkey][$key];    
		}
		$string .= '--------------------';
		return $string;
	}
	// public function __get($property) {
 //    	if (property_exists($this, $property)) {
 //    		FM_Log(__METHOD__);
 //      		return $this->$property;
 //    	}
 //  	}
}

?>