<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_mailchimp_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_mailchimp_theme_setup', 1 );
	function jacqueline_mailchimp_theme_setup() {
		if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins',					'jacqueline_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'jacqueline_exists_mailchimp' ) ) {
	function jacqueline_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_mailchimp_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_mailchimp_required_plugins');
	function jacqueline_mailchimp_required_plugins($list=array()) {
		if (in_array('mailchimp', (array)jacqueline_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'jacqueline'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}

?>