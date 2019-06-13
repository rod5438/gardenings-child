<?php
/*
 * Template Name: Author diary form
 */
?>

<?php

function getForm () {
	$formPath = 'wp-content/themes/gardenings-child/author_diary_form.html';
	FM_Log(__METHOD__,'form path:'.$formPath);
	$html = file_get_contents($formPath);
	echo $html;
}

function isUserSubmitForm () {
	$isUserSubmitForm = 
	FM_HttpPost::isSetPost('title') &&
	FM_HttpPost::isSetPost('diary') &&
	FM_HttpPost::isSetFile('thumbnailImage') &&
	FM_HttpPost::isSetFiles('diaryImages');
	FM_HttpPost::isSetPost('highlight_title_1') &&
	FM_HttpPost::isSetPost('highlight_text_1') &&
	FM_HttpPost::isSetFile('highlight_image_1');
	// wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' );
	FM_Log(__METHOD__, $isUserSubmitForm == false ? 'It is not user submit form' : 'It is user submit form');
	return $isUserSubmitForm;
}

if (isUserSubmitForm()) {
	$diaryPost = new PM_DiaryPost();
	$diaryPost->title = FM_HttpPost::post('title');
	$diaryPost->diary = FM_HttpPost::post('diary');
	$diaryPost->thumbnailImage = new FM_File('thumbnailImage');
	$diaryPost->diaryImages = new FM_Files('diaryImages');
	$diaryPost->highlightEvents = FM_HighlightEvent::getHighlightEvents();
	$diaryPost->createWPPost();
	// $post_link = get_permalink($post_id);
	// header('Location:'.$post_link);
}
else {
	getForm();
}
	// gardenings_breadcrumbs();
?>

<?php get_footer(); ?>
