<?php
/*
 * Template Name: Author diary form
 */
?>

<?php
	function getForm () {
		$form_html_string = '<div style="margin: 60px">
								<form action="" target="_self" method="post">
								標題:<br>
								<input type="text" name="title" value="我的日記" required><br>
								日記第一段:<br>
								<textarea rows="20" name="diary1" required>日記第一段</textarea><br>
								日記第二段:<br>
								<textarea rows="20" name="diary2"></textarea><br>
								<input type="submit" value="送出班長日記"><br>
								</form>
							</div>';
		echo $form_html_string;
	}
	function canShowForm () {
		return empty($_POST['title']) || empty($_POST['diary1'] || empty($_POST['diary2']));
	}
	function createContent () {
		$titleStyle = 'styleTitle';
		$diary1Style = 'style1';
		$diary2Style = 'style2';

		$title = $_POST['title'];
		$diary1 = $_POST['diary1'];
		$diary2 = $_POST['diary2'];

		$content = '<div>
						<p class="'.$titleStyle.'">'.$title.'</p>
						<p class="'.$diary1Style.'">'.$diary1.'</p>
						<p class="'.$diary2Style.'">'.$diary2.'</p>
					</div>';
		return $content;
	}
	function createPost () {
		$content = createContent();
		$post_id = wp_insert_post(
			array(
				'post_title'  => $_POST['title'],
				'post_type'   => 'post',
				'post_content' => $content,
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
