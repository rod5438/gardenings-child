<?php
/*
 * Template Name: Farmer Me Page
 */

?>

<link rel="stylesheet" type="text/css" href="style.css">

<style type="text/css">
	.mePage {
		width: 1000px;
		margin-left: auto;
		margin-right: auto;
		padding-top: 40px;
	}
	.farmerCard {
		width: 320px;
		height: 480px;
		flex: none;
		margin: 10px;
		border: 1px solid blue;
		text-align: center;
		padding: 10px;
	}
	.calendar {
		width: 480px;
		height: 320px;
		flex: none;
		margin: 10px;
		border: 1px solid blue;
	}
	.farmerCardAndCalendar {
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.farmerCardItem {
	}
	.experienceList {
		display: flex;
		padding: 10px;
		border: 1px solid blue;
	}
	.experienceItem {
		flex: none;
		margin: 10px;
		border: 1px solid blue;
	}
</style>

<?php
	  get_header(); 
?>

<div class="mePage">
	<div class="farmerCardAndCalendar">
		<div class="farmerCard">

			<?php 
				$author_id = get_current_user_id();
				$avatar_url = get_avatar_url($author_id, array('size' => 96));
				// echo '<div>'.$author_id.'</div>';
				// echo '<div>'.$avatar_url.'</div>';
				echo '<img class="farmerCardItem" src="'.$avatar_url.'" />';
			?> 
			<div class="farmerCardItem">農民證</div>
			<?php 
				echo '<div class="farmerCardItem">ID:'.$author_id.'</div>';
			?> 
		</div>
		<div class="calendar">行事曆</div>
	</div>
	<div>經歷</div>
	<div class="experienceList">
		<div class="experienceItem">香蕉</div>
		<div class="experienceItem">芭樂</div>
		<div class="experienceItem">西瓜</div>
	</div>
	<div>活耀度圖表</div>
	<div>圖</div>
</div>

<?php
      gardenings_breadcrumbs();
?>

<?php get_footer(); ?>