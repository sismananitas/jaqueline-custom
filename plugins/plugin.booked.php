<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_booked_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_booked_theme_setup', 1 );
	function jacqueline_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (jacqueline_exists_booked()) {
			add_action('jacqueline_action_add_styles', 					'jacqueline_booked_frontend_scripts');
			add_action('jacqueline_action_shortcodes_list',				'jacqueline_booked_reg_shortcodes');
			if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
				add_action('jacqueline_action_shortcodes_list_vc',		'jacqueline_booked_reg_shortcodes_vc');
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins',				'jacqueline_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'jacqueline_exists_booked' ) ) {
	function jacqueline_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_booked_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_booked_required_plugins');
	function jacqueline_booked_required_plugins($list=array()) {
		if (in_array('booked', (array)jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/booked.zip');
			if (!empty($path) && file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booked', 'jacqueline'),
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false,
                    'version'   => '2.2.5'
					);
			}
			$path = jacqueline_get_file_dir( 'plugins/install/booked-calendar-feeds.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'Booked Calendar Feeds', 'jacqueline' ),
					'slug'     => 'booked-calendar-feeds',
					'source'   => $path,
					'version'  => '1.1.6',
					'required' => false,
				);
			}
			$path = jacqueline_get_file_dir( 'plugins/install/booked-frontend-agents.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'Booked Front-End Agents', 'jacqueline' ),
					'slug'     => 'booked-frontend-agents',
					'source'   => $path,
					'version'  => '1.1.16',
					'required' => false,
				);
			}
			$path = jacqueline_get_file_dir( 'plugins/install/booked-woocommerce-payments.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'WooCommerce addons - Booked Payments with WooCommerce', 'jacqueline' ),
					'slug'     => 'booked-woocommerce-payments',
					'source'   => $path,
					'version'  => '1.5.3',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'jacqueline_booked_frontend_scripts' ) ) {
	//Handler of add_action( 'jacqueline_action_add_styles', 'jacqueline_booked_frontend_scripts' );
	function jacqueline_booked_frontend_scripts() {
		if (file_exists(jacqueline_get_file_dir('css/plugin.booked.css')))
            wp_enqueue_style( 'jacqueline-plugin-booked-style',  jacqueline_get_file_url('css/plugin.booked.css'), array(), null );
	}
}




// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_booked_calendars' ) ) {
	function jacqueline_get_list_booked_calendars($prepend_inherit=false) {
		return jacqueline_exists_booked() ? jacqueline_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('jacqueline_booked_reg_shortcodes')) {
	//Handler of add_filter('jacqueline_action_shortcodes_list',	'jacqueline_booked_reg_shortcodes');
	function jacqueline_booked_reg_shortcodes() {
		if (jacqueline_storage_isset('shortcodes')) {

			$booked_cals = jacqueline_get_list_booked_calendars();

			jacqueline_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", 'jacqueline'),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", 'jacqueline'),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			jacqueline_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", 'jacqueline'),
				"desc" => esc_html__("Insert booked calendar", 'jacqueline'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", 'jacqueline'),
						"desc" => esc_html__("Select booked calendar to display", 'jacqueline'),
						"value" => "0",
						"type" => "select",
						"options" => jacqueline_array_merge(array(0 => esc_html__('- Select calendar -', 'jacqueline')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", 'jacqueline'),
						"desc" => esc_html__("Year to display on calendar by default", 'jacqueline'),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", 'jacqueline'),
						"desc" => esc_html__("Month to display on calendar by default", 'jacqueline'),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('jacqueline_booked_reg_shortcodes_vc')) {
	//Handler of add_filter('jacqueline_action_shortcodes_list_vc',	'jacqueline_booked_reg_shortcodes_vc');
	function jacqueline_booked_reg_shortcodes_vc() {

		$booked_cals = jacqueline_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", 'jacqueline'),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", 'jacqueline'),
				"category" => esc_html__('Content', 'jacqueline'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends Jacqueline_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", 'jacqueline'),
				"description" => esc_html__("Insert booked calendar", 'jacqueline'),
				"category" => esc_html__('Content', 'jacqueline'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", 'jacqueline'),
						"description" => esc_html__("Select booked calendar to display", 'jacqueline'),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip(jacqueline_array_merge(array(0 => esc_html__('- Select calendar -', 'jacqueline')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", 'jacqueline'),
						"description" => esc_html__("Year to display on calendar by default", 'jacqueline'),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", 'jacqueline'),
						"description" => esc_html__("Month to display on calendar by default", 'jacqueline'),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends Jacqueline_VC_ShortCodeSingle {}

	}
}
?>