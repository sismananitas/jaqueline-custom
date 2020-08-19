<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_vc_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_vc_theme_setup', 1 );
	function jacqueline_vc_theme_setup() {
		if (jacqueline_exists_visual_composer()) {
			add_action('jacqueline_action_add_styles',		 				'jacqueline_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins',					'jacqueline_vc_required_plugins' );
			add_filter( 'jacqueline_filter_required_plugins',					'jacqueline_vc_required_plugins_bundle' );
		}
	}
}

// Check if WPBakery PageBuilder installed and activated
if ( !function_exists( 'jacqueline_exists_visual_composer' ) ) {
	function jacqueline_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery PageBuilder in frontend editor mode
if ( !function_exists( 'jacqueline_vc_is_frontend' ) ) {
	function jacqueline_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_vc_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_vc_required_plugins');
	function jacqueline_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', (array)jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPBakery PageBuilder', 'jacqueline'),
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false,
                    'version'   => '6.0.5'
				);
			}
		}
		return $list;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_vc_required_plugins_bundle' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_vc_required_plugins_bundle');
	function jacqueline_vc_required_plugins_bundle($list=array())
    {
        if (in_array('vc-extensions-bundle', (array)jacqueline_storage_get('required_plugins'))) {
            $path = jacqueline_get_file_dir('plugins/install/vc-extensions-bundle.zip');
            if (file_exists($path)) {
                $list[] = array(
                    'name' => 'VC Extensions Bundle',
                    'slug' => 'vc-extensions-bundle',
                    'source' => $path,
                    'required' => false
                );
            }
            return $list;
        }
    }
}

// Enqueue VC custom styles
if ( !function_exists( 'jacqueline_vc_frontend_scripts' ) ) {
	//Handler of add_action( 'jacqueline_action_add_styles', 'jacqueline_vc_frontend_scripts' );
	function jacqueline_vc_frontend_scripts() {
		if (file_exists(jacqueline_get_file_dir('css/plugin.visual-composer.css')))
            wp_enqueue_style( 'jacqueline-plugin-visual-composer-style',  jacqueline_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}

?>