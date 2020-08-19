<?php
/**
 * Jacqueline Framework: messages subsystem
 *
 * @package	jacqueline
 * @since	jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('jacqueline_messages_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_messages_theme_setup' );
	function jacqueline_messages_theme_setup() {
		// Core messages strings
		add_action('jacqueline_action_add_scripts_inline', 'jacqueline_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('jacqueline_get_error_msg')) {
	function jacqueline_get_error_msg() {
		return jacqueline_storage_get('error_msg');
	}
}

if (!function_exists('jacqueline_set_error_msg')) {
	function jacqueline_set_error_msg($msg) {
		$msg2 = jacqueline_get_error_msg();
		jacqueline_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('jacqueline_get_success_msg')) {
	function jacqueline_get_success_msg() {
		return jacqueline_storage_get('success_msg');
	}
}

if (!function_exists('jacqueline_set_success_msg')) {
	function jacqueline_set_success_msg($msg) {
		$msg2 = jacqueline_get_success_msg();
		jacqueline_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('jacqueline_get_notice_msg')) {
	function jacqueline_get_notice_msg() {
		return jacqueline_storage_get('notice_msg');
	}
}

if (!function_exists('jacqueline_set_notice_msg')) {
	function jacqueline_set_notice_msg($msg) {
		$msg2 = jacqueline_get_notice_msg();
		jacqueline_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('jacqueline_set_system_message')) {
	function jacqueline_set_system_message($msg, $status='info', $hdr='') {
		update_option('jacqueline_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('jacqueline_get_system_message')) {
	function jacqueline_get_system_message($del=false) {
		$msg = get_option('jacqueline_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			jacqueline_del_system_message();
		return $msg;
	}
}

if (!function_exists('jacqueline_del_system_message')) {
	function jacqueline_del_system_message() {
		delete_option('jacqueline_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('jacqueline_messages_add_scripts_inline')) {
	function jacqueline_messages_add_scripts_inline($vars=array()) {
		// Strings for translation
		$vars["strings"] = array(
			'ajax_error' => esc_html__('Invalid server answer', 'jacqueline'),
			'bookmark_add' => esc_html__('Add the bookmark', 'jacqueline'),
			'bookmark_added' => esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'jacqueline'),
			'bookmark_del' => esc_html__('Delete this bookmark', 'jacqueline'),
			'bookmark_title' => esc_html__('Enter bookmark title', 'jacqueline'),
			'bookmark_exists' => esc_html__('Current page already exists in the bookmarks list', 'jacqueline'),
			'search_error' => esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'jacqueline'),
			'email_confirm' => esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'jacqueline'),
			'reviews_vote' => esc_html__('Thanks for your vote! New average rating is:', 'jacqueline'),
			'reviews_error' => esc_html__('Error saving your vote! Please, try again later.', 'jacqueline'),
			'error_like' => esc_html__('Error saving your like! Please, try again later.', 'jacqueline'),
			'error_global' => esc_html__('Global error text', 'jacqueline'),
			'name_empty' => esc_html__('The name can\'t be empty', 'jacqueline'),
			'name_long' => esc_html__('Too long name', 'jacqueline'),
			'email_empty' => esc_html__('Too short (or empty) email address', 'jacqueline'),
			'email_long' => esc_html__('Too long email address', 'jacqueline'),
			'email_not_valid' => esc_html__('Invalid email address', 'jacqueline'),
			'subject_empty' => esc_html__('The subject can\'t be empty', 'jacqueline'),
			'subject_long' => esc_html__('Too long subject', 'jacqueline'),
			'text_empty' => esc_html__('The message text can\'t be empty', 'jacqueline'),
			'text_long' => esc_html__('Too long message text', 'jacqueline'),
			'send_complete' => esc_html__("Send message complete!", 'jacqueline'),
			'send_error' => esc_html__('Transmit failed!', 'jacqueline'),
			'login_empty' => esc_html__('The Login field can\'t be empty', 'jacqueline'),
			'login_long' => esc_html__('Too long login field', 'jacqueline'),
			'login_success' => esc_html__('Login success! The page will be reloaded in 3 sec.', 'jacqueline'),
			'login_failed' => esc_html__('Login failed!', 'jacqueline'),
			'password_empty' => esc_html__('The password can\'t be empty and shorter then 4 characters', 'jacqueline'),
			'password_long' => esc_html__('Too long password', 'jacqueline'),
			'password_not_equal' => esc_html__('The passwords in both fields are not equal', 'jacqueline'),
			'registration_success' => esc_html__('Registration success! Please log in!', 'jacqueline'),
			'registration_failed' => esc_html__('Registration failed!', 'jacqueline'),
			'geocode_error' => esc_html__('Geocode was not successful for the following reason:', 'jacqueline'),
			'googlemap_not_avail' => esc_html__('Google map API not available!', 'jacqueline'),
			'editor_save_success' => esc_html__("Post content saved!", 'jacqueline'),
			'editor_save_error' => esc_html__("Error saving post data!", 'jacqueline'),
			'editor_delete_post' => esc_html__("You really want to delete the current post?", 'jacqueline'),
			'editor_delete_post_header' => esc_html__("Delete post", 'jacqueline'),
			'editor_delete_success' => esc_html__("Post deleted!", 'jacqueline'),
			'editor_delete_error' => esc_html__("Error deleting post!", 'jacqueline'),
			'editor_caption_cancel' => esc_html__('Cancel', 'jacqueline'),
			'editor_caption_close' => esc_html__('Close', 'jacqueline')
			);
		return $vars;
	}
}
?>