<?php
class PM_DiaryPost {
	public $postId; 			// string
	public $title;
	public $thumbnailImage; 	// File
	public $diaryImages; 		// Files
	public $highlightEvents; 	// array
	function createWPPost () {
		FM_Log(__METHOD__);
		$this->thumbnailImage->media_handle_upload();
		$this->diaryImages->media_handle_upload();
		foreach ($this->highlightEvents as $highlight_event) {
			$highlight_event->media_handle_upload();
		}
		$content = $this->getCSSContent();
		$this->postId = wp_insert_post(
			array(
				'post_title'	=> $this->title,
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
	function getHighlightEventsHTML($style, array $highlightEvents) {
		$eventsHtml = '';
		foreach ($highlightEvents as $highlightEvent) {
			$eventsHtml.= $this->getHighlightEventHTML($style, $highlightEvent);
		}
		return $eventsHtml;
	}
	function getHighlightEventHTML(string $style, FM_HighlightEvent $highlightEvent) {
		FM_Log(__METHOD__, 'Post key:'.$highlightEvent->image->postkey.' Style:'.$style);
		if (!$highlightEvent->image->isUploaded()) {
			return '';
		}
		$imageSrc = wp_get_attachment_image_src($highlightEvent->image->postId);
		$eventHtml =
		'<div>
			<p class="" >'.$highlightEvent->title.'</p>
			<p class="" >'.$highlightEvent->text.'</p>
			'.$this->getImageHTML($style, $highlightEvent->image).'
		</div>';
		return $eventHtml;
	}
	private function getCSSContent () {
		FM_Log(__METHOD__);
		$title_style = 'title_style';
		$diary_style = 'diary_style';
		$image_style = 'image_style';
		$images_container_style = 'images_container_style';
		$content = '<div>
						<div>
							<p class="'.$title_style.'">'.$this->title.'</p>
							<p class="'.$diary_style.'">'.$this->diary.'</p>
							<div class="'.$images_container_style.'">
								'.$this->getImagesHTML($image_style, $this->diaryImages).'
							</div>
							</div>
						<div>
							地點頁連結
						</div>
						<div>
							'.$this->getHighlightEventsHTML($image_style, $this->highlightEvents).'
						</div>
						<div>
							活動地點資訊
						</div>
					</div>';
		return $content;
	}
}
?>