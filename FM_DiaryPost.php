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
	function getImagesHTML(FM_Files $files) : string {
		FM_Log(__METHOD__, 'Post key:'.$files->postkey);
		if (!$files->isUploaded()) {
			return '';
		}
		$imagesHTML = '';
		foreach ($files->postIds as $postId) {
			$imagesHTML .= $this->getImgHTML($postId);
		}
		return $imagesHTML;
	}
	function getImageHTML(FM_File $file) : string {
		FM_Log(__METHOD__, 'Post key:'.$file->postkey);
		if (!$file->isUploaded()) {
			return '';
		}
		return $this->getImgHTML($file->postId);
	}
	function getImgHTML(string $postId) : string {
		FM_Log(__METHOD__, 'Post id:'.$postId);
		$imageSrc = wp_get_attachment_image_src($postId);
		return '<div>
					<img src="'.$imageSrc[0].'">
				</div>';
	}
	function getHighlightEventsHTML(array $highlightEvents) {
		$eventsHtml = '<div>';
		foreach ($highlightEvents as $highlightEvent) {
			$eventsHtml.= $this->getHighlightEventHTML($highlightEvent);
		}
		$eventsHtml.='</div>';
		return $eventsHtml;
	}
	function getTitleTextLinkHTML (FM_HighlightEvent $highlightEvent) {
		$html = 
			'<div>
				<h3>'.$highlightEvent->title.'</h3>
				<div>
					<p>'.$highlightEvent->text.'</p>
				</div>
				<div>
					<a href="https://kids-career.com.tw/author_diary/" role="button"><span>Read More</span></a>
				</div>
			</div>';

		return $html;
	}
	function getHighlightEventHTML(FM_HighlightEvent $highlightEvent) {
		FM_Log(__METHOD__, 'Post key:'.$highlightEvent->image->postkey);
		if (!$highlightEvent->image->isUploaded()) {
			return '';
		}
		$imageSrc = wp_get_attachment_image_src($highlightEvent->image->postId);
		$html =
		'<div>
		    '.$this->getTitleTextLinkHTML($highlightEvent).'
		    <div>
				'.$this->getImageHTML($highlightEvent->image).'
			</div>
		</div>';
		return $html;

	}
	private function getCSSContent () {
		FM_Log(__METHOD__);
		$title_style = 'fm-diary-title';
		$diary_style = 'fm-diary-text';
		$block_style = 'fm-empty-block';
		$images_container_style = 'fm-diary-images-container';
		$highlight_event_section_style = 'fm-highlight-event-section';
		$section_style = 'fm-diary-section';
		$content = '<div>
						<section>
							<div>
								十全十美班長
							</div>
						</section>

						<section class="'.$section_style.'">
							<div></div>
							<div>
								<h2 class="'.$title_style.'">'.$this->title.'</h2>
								<div class="'.$block_style.'"></div>
								<div class="'.$diary_style.'">'.$this->diary.'</div>
								<div class="'.$images_container_style.'">'.$this->getImagesHTML($this->diaryImages).'</div>
							</div>
						</section>

						<section>
							<div>
								地點頁連結
							</div>
						</section>
						<section class="'.$highlight_event_section_style.'">
							'.$this->getHighlightEventsHTML($this->highlightEvents).'
						</section>
						<section>
							<div>
								活動地點資訊
							</div>
						</section>
					</div>';
		return $content;
	}
}
?>