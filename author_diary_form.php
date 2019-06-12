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
 function getForm () {
 	error_log('getForm');
 	$form_html_string = file_get_contents('wp-content/themes/gardenings-child/author_diary_form.html');
 	echo $form_html_string;
 }
 function logForPost() {
 	error_log('logForPost');
 	post_log('diary');
 	post_file_log('thumbnailImage');
 	post_files_log('diaryImages');
		// post_log('highlight_title_1');
		// post_log('highlight_text_1');
		// post_files_log('highlight_image_1');
 }
 function isSetPost($post_key) {
 	return isset($_POST['title']);
 }
 function isSetFiles($post_key) {
 	$count = count($_FILES[$post_key]['size']);
 	for ($i=0; $i < $count ; $i++) { 
 		if ($_FILES[$post_key]['size'] == 0) {
 			return false;
 		}
 	}
 	return true;
 }
 function isSetFile($post_key) {
	if ($_FILES[$post_key]['size'] == 0) {
		return false;
	}
 	return true;
 }
	// function isSetFile($post_key) {
	// 	return $_FILES[$post_key]['size'] > 0 ? true : false;
	// }
 function isUserSubmitForm () {
 	logForPost();
 	$isUserSubmitForm = isSetPost('title') &&
 	isSetPost('diary') &&
 	isSetFile('thumbnailImage') &&
 	isSetFiles('diaryImages');
		// isset($_POST['highlight_title_1']) && 
		// isset($_POST['highlight_text_1']) &&
		// ($_FILES['highlight_image_1']['size'] > 0);
		// wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' );
 	error_log($isUserSubmitForm == false ? 'isNotUserSubmitForm' : 'isUserSubmitForm');
 	return $isUserSubmitForm;
 }
 function getHighlightEvents() {
 	$highlight1 = new HighlightEvent();
 	$highlight1->image_id = '';
 	$highlight1->title = $_POST['highlight_title_1'];
 	$highlight1->text = $_POST['highlight_text_1'];

 	$highlight2 = new HighlightEvent();
 	$highlight2->image_id = '';
 	$highlight2->title = $_POST['highlight_title_2'];
 	$highlight2->text = $_POST['highlight_text_2'];

 	$highlight3 = new HighlightEvent();
 	$highlight3->image_id = '';
 	$highlight3->title = $_POST['highlight_title_3'];
 	$highlight3->text = $_POST['highlight_text_3'];

 	return $arrayName = array($highlight1 , $highlight2, $highlight3);
 }

 class File {
 	public $post_key; // the key of http post
	public $post_id; // wordpress post id or attachment id
	private $file;
	function File ($post_key) {
		$this->post_key = $post_key;
		$this->file = $_FILES[$this->post_key];
	}
	function media_handle_upload() {
		require_once_for_media_handle_upload();
		$this->post_id = media_handle_upload( $this->post_key, 0 );
		if ( is_wp_error( $this->post_id ) ) {
			error_log('cannot upload attachment '.$this->post_key.':'.$this->file['name']);
		}
		else {
			error_log('Upload attachment'.$this->post_key.':'.$this->file['name'].' post id:'.$this->post_id);
		}
	}
}

class Files {
	public $post_key; // the key of http post
	public $post_ids; // wordpress post id or attachment id
	private $row_files; // same as $_FILES[$this->post_key];
	private $all_keys; // name, type, tmp_name, error, size
	private $files;
	function Files ($post_key) {
		$this->post_key = $post_key;
		$this->row_files = $_FILES[$this->post_key];
		$this->all_keys = array_keys($this->row_files);
		$this->files = $this->getFiles();
		$this->post_ids = array();
	}
	function media_handle_upload() {
		foreach ($this->files as $key => $file) {
			require_once_for_media_handle_upload();
			$_FILES = array_merge($_FILES, array('upload_file' => $file));
			$attachment_id = media_handle_upload('upload_file', 0);
			array_pop($_FILES);
			if (is_wp_error($attachment_id)) {
				error_log('cannot upload attachment '.$this->post_key.':'.$file['name']);
			} 
			else {
				error_log('Upload attachment '.$this->post_key.':'.$file['name']);
				array_push($this->post_ids, $attachment_id);
			}
		}
	}
	private function count () {
		return count($this->row_files[$this->all_keys[0]]);
	}
	private function getFiles() {
		$files = array();
		for ($index = 0; $index < $this->count(); $index++) {
			$file = array();		
			foreach ($this->all_keys as $value) {
				$file = array_merge($file, array($value => $this->row_files[$value][$index]));	
			}
			array_push($files, $file);
		}
		return $files;
	}
}

class HighlightEvent {
	public $file;
	public $title;
	public $text;
	public $link;
	function media_handle_upload () {

	}
}

class DiaryPost {
	public $post_id; 			// string
	public $thumbnail_image; 	// File
	public $diary_images; 		// Files
	public $highlight_events; 	// array
	function createPost () {
 		error_log('createPost');
 		$this->thumbnail_image->media_handle_upload();
 		$this->diary_images->media_handle_upload();
 		foreach ($this->highlight_events as $highlight_event) {
 			$highlight_event->media_handle_upload();
 		}
 		$content = $this->getCSSContent();
 		$this->post_id = wp_insert_post(
 			array(
 				'post_title'	=> $_POST['title'],
 				'post_type'		=> 'post',
 				'post_content'	=> $content,
 				'post_status'	=> 'publish',
 			)
 		);
 		if (set_post_thumbnail($this->post_id, $this->thumbnail_image->post_id)) {
 			error_log('set_post_thumbnail OK');
 		}
 		else {
			error_log('set_post_thumbnail fail post_id:'.$this->post_id.'thumbnail post_id:'.$this->thumbnail_image->post_id);
 		}
 	}
 	function getDiaryImagesHTML($style) {
 		$imagesHTML = '';
 		foreach ($this->diary_images->post_ids as $image_id) {
 			$imageSrc = wp_get_attachment_image_src($image_id);
 			$imagesHTML .= '<div><img class="'.$style.'"src="'.$imageSrc[0].'"></div>';
 		}
 		return $imagesHTML;
 	}
 	function getHighlightEventsHTML($style) {
 		return '';
	}
 	private function getCSSContent () {
 		error_log('createCSSContent');
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
 								'.$this->getDiaryImagesHTML($image_style, $diary_image_ids).'
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
		$diaryPost = new DiaryPost();
		$diaryPost->thumbnail_image = new File('thumbnailImage');
		$diaryPost->diary_images = new Files('diaryImages');
		$diaryPost->highlight_events = getHighlightEvents();
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
