<?php
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
?>