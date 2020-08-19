<?php
/* The GDPR Framework support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_gdpr_framework_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_gdpr_framework_theme_setup', 1 );
	function jacqueline_gdpr_framework_theme_setup() {
		if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins', 'jacqueline_gdpr_framework_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'jacqueline_exists_gdpr_framework' ) ) {
	function jacqueline_exists_gdpr_framework() {
		return defined( 'GDPR_FRAMEWORK_VERSION' );
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_gdpr_framework_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_gdpr_framework_required_plugins');
	function jacqueline_gdpr_framework_required_plugins($list=array()) {
		if (in_array('gdpr_framework', (array)jacqueline_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('The GDPR Framework', 'jacqueline'),
					'slug' 		=> 'gdpr-framework',
					'required' 	=> false
				);
		return $list;
	}
}
