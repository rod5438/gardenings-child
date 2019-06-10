<?php
/*
 * Template Name: Author diary form
 */

?>

<style type="text/css">
</style>

<?php
	function getForm () {
		$form_html_string = '<div style="margin: 60px">
								<form action="" target="_self" method="post">
								標題:<br>
								<input type="text" name="title"><br>
								日記:<br>
								<textarea rows="20" name="diary"></textarea><br>
								<input type="submit" value="送出班長日記"><br>
								</form>
							</div>';
		echo $form_html_string;
	}
	function canShowForm () {
		return empty($_POST['title']) || empty($_POST['diary']);
	}
	function createPost () {
		$post_id = wp_insert_post(
			array(
				'post_title'  => $_POST['title'],
				'post_type'   => 'post',
				'post_content' => $_POST['diary'],
				'post_status' => 'publish',
			)
		);
		echo $post_id;
		return $post_id;
	}
?>

<?php
	if (canShowForm()) {
		getForm();
	}
	else {
		$post_id = createPost();
		$post_link = get_permalink($post_id);
		echo '<div style="margin: 60px">'.$post_link.' </div>';
		header('Location:'.$post_link);
	}
	// gardenings_breadcrumbs();
?>

<?php get_footer(); ?>
