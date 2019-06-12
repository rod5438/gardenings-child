<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
	function chld_thm_cfg_locale_css( $uri ){
		if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
			$uri = get_template_directory_uri() . '/rtl.css';
		return $uri;
	}
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
	function chld_thm_cfg_parent_css() {
		wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'bootstrap','jquery-owl-css' ) );
	}
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

function require_once_for_media_handle_upload() {
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
}

function post_files_log($post_key) {
	error_log('=========='.$post_key.'==========');
	$allKey = array('name', 'type', 'tmp_name', 'error', 'size');
	$count = count($_FILES[$post_key][$allKey[0]]);
	for ($i=0; $i < $count; $i++) { 
		error_log('----------file['.$i.']----------');
		foreach ($allKey as $key => $value) {
			error_log($post_key.'['.$value.']'.':'.$_FILES[$post_key][$value][$i]);    
		}
		error_log('--------------------');
	}
	error_log('====================');
}
function post_file_log($post_key) {
	error_log('=========='.$post_key.'==========');
	$allKey = array_keys($_FILES[$post_key]);
	foreach ($allKey as $key) {
		error_log($post_key.'['.$key.']'.':'.$_FILES[$post_key][$key]);    
	}
	error_log('--------------------');
}
function post_log($post_key) {
	error_log($post_key.':'.$_POST[$post_key]);
}

// END ENQUEUE PARENT ACTION
