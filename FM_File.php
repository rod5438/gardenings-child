<?php

class FM_File {
 	public $postkey; // the key of http post
	public $postId; // wordpress post id or attachment id
	private $file;
	function FM_File ($postkey) {
		FM_Log(__METHOD__, 'Post key:'.$postkey);
		$this->postkey = $postkey;
		$this->file = FM_HttpPost::inst()->file($postkey);
		$this->postId = 0;
	}
	function media_handle_upload() {
		FM_Log(__METHOD__, 'Post key:'.$this->postkey.' File name:'.$this->file['name'].' Uploading.');
		require_once_for_media_handle_upload();
		$this->postId = media_handle_upload( $this->postkey, 0 );
		if (is_wp_error($this->postId)) {
			FM_Log(__METHOD__, 'Post key:'.$this->postkey.' File name:'.$this->file['name'].' Cannot upload.');
		}
		else {
			FM_Log(__METHOD__, 'Post key:'.$this->postkey.' File name:'.$this->file['name'].' Post id:'.$this->postId.' Uploaded.');
		}
	}
	function isUploaded() {
		if ($this->postId == 0 || is_wp_error($this->postId)) {
			FM_Log(__METHOD__, 'Post key:'.$this->postkey.' File name:'.$this->file['name'].' postId is '.$this->postId.'. Haven\'t call media_handle_upload yet.');
			return false;
		}
		return true;
	}
	// public function __get($property) {
 //    	if (property_exists($this, $property)) {
 //    		if (strcmp($property, 'postId') && !isset($this->property)) {
 //    			FM_Log(__METHOD__, 'postId is null. Haven call media_handle_upload yet. Postkey:'.$this->postkey);
 //    		}
 //      		return $this->$property;
 //    	}
 //  	}
}
?>