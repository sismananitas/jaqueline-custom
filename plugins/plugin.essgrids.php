<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_essgrids_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_essgrids_theme_setup', 1 );
	function jacqueline_essgrids_theme_setup() {
		// Register shortcode in the shortcodes list
		if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins',				'jacqueline_essgrids_required_plugins' );
		}
	}
}


// Check if Ess. Grid installed and activated
if ( !function_exists( 'jacqueline_exists_essgrids' ) ) {
	function jacqueline_exists_essgrids() {
		return defined('EG_PLUGIN_PATH');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_essgrids_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_essgrids_required_plugins');
	function jacqueline_essgrids_required_plugins($list=array()) {
		if (in_array('essgrids', (array)jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/essential-grid.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Essential Grid', 'jacqueline'),
					'slug' 		=> 'essential-grid',
					'source'	=> $path,
					'required' 	=> false,
                    'version'   => '2.3.3'
					);
			}
		}
		return $list;
	}
}

?>