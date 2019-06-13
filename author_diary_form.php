<?php
/*
 * Template Name: Author diary form
 */
?>

<!-- 


type
tmp_name
error
size

 -->

 <?php

 function FM_Log (string $method, string $message = '') {
 	$paddingLen = strlen($method) < 20 ? 20 : 
 		strlen($method) >= 20 && strlen($method) < 40 ? 40 :
 	 	strlen($method) >= 40 && strlen($method) < 60 ? 60 :
 	 	80;
 	$method_pad = str_pad($method , $paddingLen, " " , STR_PAD_RIGHT);
	error_log('[FM_Log]['.$method_pad.']'.(strlen($message) == 0 ? '' : '['.$message.']'));
 }

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
 function getForm () {
 	$formPath = 'wp-content/themes/gardenings-child/author_diary_form.html';
 	FM_Log(__METHOD__,'form path:'.$formPath);
 	$html = file_get_contents($formPath);
 	echo $html;
 }

 function isUserSubmitForm () {
 	$isUserSubmitForm = FM_HttpPost::inst()->isSetPost('title') &&
 	FM_HttpPost::inst()->isSetPost('diary') &&
 	FM_HttpPost::inst()->isSetFile('thumbnailImage') &&
 	FM_HttpPost::inst()->isSetFiles('diaryImages');
		// isset($_POST['highlight_title_1']) && 
		// isset($_POST['highlight_text_1']) &&
		// ($_FILES['highlight_image_1']['size'] > 0);
		// wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' );
 	FM_Log(__METHOD__, $isUserSubmitForm == false ? 'It is not user submit form' : 'It is user submit form');
 	return $isUserSubmitForm;
 }

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

class FM_HighlightEvent {
	public $image; // File
	public $title;
	public $text;
	public $link;
	function FM_HighlightEvent (int $index) {
		FM_Log(__METHOD__, 'index:'.$index);
		$this->title = FM_HttpPost::inst()->post('highlight_title_'.$index);
		$this->text = FM_HttpPost::inst()->post('highlight_text_'.$index);
		$this->image = new FM_File('highlight_image_'.$index);
	}
	function media_handle_upload () {
		FM_Log(__METHOD__, 'index:'.$index);
		$this->image->media_handle_upload();
	}
	private function content_complete () {
		return true;
	} 
	public static function getHighlightEvents() { // three highlight
		$highlightEvents = array();
		foreach (range(1, 3) as $index) {
			$highlightEvent = new FM_HighlightEvent($index);
			if ($highlightEvent->content_complete()) {
				array_push($highlightEvents, $highlightEvent);
			}
		}
		return $highlightEvents;
	}
}

class PM_DiaryPost {
	public $postId; 			// string
	public $thumbnailImage; 	// File
	public $diaryImages; 		// Files
	public $highlightEvents; 	// array
	function createPost () {
 		FM_Log(__METHOD__);
 		$this->thumbnailImage->media_handle_upload();
 		$this->diaryImages->media_handle_upload();
 		foreach ($this->highlightEvents as $highlight_event) {
 			$highlight_event->media_handle_upload();
 		}
 		$content = $this->getCSSContent();
 		$this->postId = wp_insert_post(
 			array(
 				'post_title'	=> $_POST['title'],
 				'post_type'		=> 'post',
 				'post_content'	=> $content,
 				'post_status'	=> 'publish',
 			)
 		);

 		if (is_wp_error($this->postId)) {
 			FM_Log(__METHOD__, 'Insert post NG post id:'.$this->postId);
 		}
 		else {
 			FM_Log(__METHOD__, 'Insert post OK post id:'.$this->postId);
 		}

 		if (set_post_thumbnail($this->postId, $this->thumbnailImage->postId)) {
 			FM_Log(__METHOD__, 'Set post thumbnail OK:'.$this->thumbnailImage->postId);
 		}
 		else {
			FM_Log(__METHOD__, 'Set post thumbnail NG:'.$this->thumbnailImage->postId);
 		}
 	}
 	 function getImagesHTML(string $style, FM_Files $files) : string {
 	 	FM_Log(__METHOD__, 'Post key:'.$files->postkey);
 	 	if (!$files->isUploaded()) {
 	 		return '';
 	 	}
 		$imagesHTML = '';
 		foreach ($files->postIds as $postId) {
 			$imagesHTML .= $this->getImgHTML($style, $postId);
 		}
 		return $imagesHTML;
 	}
 	function getImageHTML(string $style, FM_File $file) : string {
 		FM_Log(__METHOD__, 'Post key:'.$file->postkey);
 		if (!$file->isUploaded()) {
 	 		return '';
 	 	}
 		return $this->getImgHTML($style, $file->postId);
 	}
 	function getImgHTML(string $style, string $postId) : string {
 		FM_Log(__METHOD__, 'Post id:'.$postId.' Style:'.$style);
 		$imageSrc = wp_get_attachment_image_src($postId);
 		return '<img class="'.$style.'"src="'.$imageSrc[0].'">';
 	}
 	function getHighlightEventsHTML($style) {
 		$eventsHtml = '';
 		foreach ($this->highlightEvents as $highlightEvent) {
 			$imageSrc = wp_get_attachment_image_src($highlightEvent->image->postId);
 			$eventHtml = 	'<div>
 								<p class="" >'.$highlightEvent->title.'</p>
 								<p class="" >'.$highlightEvent->text.'</p>
 								'.$this->getImageHTML($style, $highlightEvent->image).'
 							</div>';
 			$eventsHtml.= $eventHtml;
 		}
 		return $eventsHtml;
	}
 	private function getCSSContent () {
 		FM_Log(__METHOD__);
 		$title_style = 'title_style';
 		$diary_style = 'diary_style';
 		$image_style = 'image_style';
 		$images_container_style = 'images_container_style';

 		$title = $_POST['title'];
 		$diary = $_POST['diary'];
 		$content = '<div>
 						<div>
 							<p class="'.$title_style.'">'.$title.'</p>
 							<p class="'.$diary_style.'">'.$diary.'</p>
 							<div class="'.$images_container_style.'">
 								'.$this->getImagesHTML($image_style, $this->diaryImages).'
 							</div>
 						</div>
 						<div>
 							地點頁連結
 						</div>
 						<div>
 							'.$this->getHighlightEventsHTML($image_style).'
 						</div>
 						<div>
 							活動地點資訊
 						</div>
 					</div>';
 		return $content;
 	}
}

?>

<?php
	if (isUserSubmitForm()) {
		$diaryPost = new PM_DiaryPost();
		$diaryPost->thumbnailImage = new FM_File('thumbnailImage');
		$diaryPost->diaryImages = new FM_Files('diaryImages');
		$diaryPost->highlightEvents = FM_HighlightEvent::getHighlightEvents();
		$diaryPost->createPost();
		// $post_link = get_permalink($post_id);
		// header('Location:'.$post_link);
	}
	else {
		getForm();
	}
	// gardenings_breadcrumbs();
?>

<?php get_footer(); ?>
