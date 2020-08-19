<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_revslider_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_revslider_theme_setup', 1 );
	function jacqueline_revslider_theme_setup() {
		if (jacqueline_exists_revslider()) {
			add_filter( 'jacqueline_filter_list_sliders',					'jacqueline_revslider_list_sliders' );
			add_filter( 'jacqueline_filter_shortcodes_params',			'jacqueline_revslider_shortcodes_params' );
			add_filter( 'jacqueline_filter_theme_options_params',			'jacqueline_revslider_theme_options_params' );
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins',				'jacqueline_revslider_required_plugins' );
		}
	}
}

if ( !function_exists( 'jacqueline_revslider_settings_theme_setup2' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_revslider_settings_theme_setup2', 3 );
	function jacqueline_revslider_settings_theme_setup2() {
		if (jacqueline_exists_revslider()) {

			// Add Revslider specific options in the Theme Options
			jacqueline_storage_set_array_after('options', 'slider_engine', "slider_alias", array(
				"title" => esc_html__('Revolution Slider: Select slider',  'jacqueline'),
				"desc" => wp_kses_data( __("Select slider to show (if engine=revo in the field above)", 'jacqueline') ),
				"override" => "category,services_group,page",
				"dependency" => array(
					'show_slider' => array('yes'),
					'slider_engine' => array('revo')
				),
				"std" => "",
				"options" => jacqueline_get_options_param('list_revo_sliders'),
				"type" => "select"
				)
			);

		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'jacqueline_exists_revslider' ) ) {
	function jacqueline_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_revslider_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_revslider_required_plugins');
	function jacqueline_revslider_required_plugins($list=array()) {
		if (in_array('revslider', (array)jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/revslider.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Revolution Slider', 'jacqueline'),
					'slug' 		=> 'revslider',
					'source'	=> $path,
					'required' 	=> false,
                    'version'   => '6.1.0'
					);
			}
		}
		return $list;
	}
}


// Lists
//------------------------------------------------------------------------

// Add RevSlider in the sliders list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_revslider_list_sliders' ) ) {
	//Handler of add_filter( 'jacqueline_filter_list_sliders',					'jacqueline_revslider_list_sliders' );
	function jacqueline_revslider_list_sliders($list=array()) {
		$list["revo"] = esc_html__("Layer slider (Revolution)", 'jacqueline');
		return $list;
	}
}

// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_revo_sliders' ) ) {
	function jacqueline_get_list_revo_sliders($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_revo_sliders'))=='') {
			$list = array();
			if (jacqueline_exists_revslider()) {
				global $wpdb;
				// Attention! The use of wpdb->prepare() is not required
				// because the query does not use external data substitution
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$list = apply_filters('jacqueline_filter_list_revo_sliders', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_revo_sliders', $list);
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Add RevSlider in the shortcodes params
if ( !function_exists( 'jacqueline_revslider_shortcodes_params' ) ) {
	//Handler of add_filter( 'jacqueline_filter_shortcodes_params',			'jacqueline_revslider_shortcodes_params' );
	function jacqueline_revslider_shortcodes_params($list=array()) {
		$list["revo_sliders"] = jacqueline_get_list_revo_sliders();
		return $list;
	}
}

// Add RevSlider in the Theme Options params
if ( !function_exists( 'jacqueline_revslider_theme_options_params' ) ) {
	//Handler of add_filter( 'jacqueline_filter_theme_options_params',			'jacqueline_revslider_theme_options_params' );
	function jacqueline_revslider_theme_options_params($list=array()) {
		$list["list_revo_sliders"] = array('$jacqueline_get_list_revo_sliders' => '');
		return $list;
	}
}
?>