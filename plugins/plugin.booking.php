<?php
/* Booking Calendar support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_booking_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_booking_theme_setup', 1 );
	function jacqueline_booking_theme_setup() {
		// Register shortcode in the shortcodes list
		if (jacqueline_exists_booking()) {
			add_action('jacqueline_action_add_styles',					'jacqueline_booking_frontend_scripts');
			add_action('jacqueline_action_shortcodes_list',				'jacqueline_booking_reg_shortcodes');
			if (function_exists('jacqueline_exists_visual_composer') && jacqueline_exists_visual_composer())
				add_action('jacqueline_action_shortcodes_list_vc',		'jacqueline_booking_reg_shortcodes_vc');
		}
		if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins',				'jacqueline_booking_required_plugins' );
		}
	}
}


// Check if Booking Calendar installed and activated
if ( !function_exists( 'jacqueline_exists_booking' ) ) {
	function jacqueline_exists_booking() {
		return function_exists('wp_booking_start_session');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_booking_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_booking_required_plugins');
	function jacqueline_booking_required_plugins($list=array()) {
		if (in_array('booking', (array)jacqueline_storage_get('required_plugins'))) {
			$path = jacqueline_get_file_dir('plugins/install/wp-booking-calendar.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booking Calendar', 'jacqueline'),
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'jacqueline_booking_frontend_scripts' ) ) {
	//Handler of add_action( 'jacqueline_action_add_styles', 'jacqueline_booking_frontend_scripts' );
	function jacqueline_booking_frontend_scripts() {
		if (file_exists(jacqueline_get_file_dir('css/plugin.booking.css')))
            wp_enqueue_style( 'jacqueline-plugin-booking-style',  jacqueline_get_file_url('css/plugin.booking.css'), array(), null );
	}
}




// Lists
//------------------------------------------------------------------------

// Return Booking categories list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_booking_categories' ) ) {
	function jacqueline_get_list_booking_categories($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_booking_cats'))=='') {
			$list = array();
			if (jacqueline_exists_booking()) {
				global $wpdb;
				// Attention! The use of wpdb->prepare() is not required
				// because the query does not use external data substitution
				$rows = $wpdb->get_results( "SELECT category_id, category_name FROM " . esc_sql($wpdb->prefix) . "booking_categories" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->category_id] = $row->category_name;
					}
				}
			}
			$list = apply_filters('jacqueline_filter_list_booking_categories', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_booking_cats', $list); 
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}

// Return Booking calendars list, prepended inherit (if need)
if ( !function_exists( 'jacqueline_get_list_booking_calendars' ) ) {
	function jacqueline_get_list_booking_calendars($prepend_inherit=false) {
		if (($list = jacqueline_storage_get('list_booking_calendars'))=='') {
			$list = array();
			if (jacqueline_exists_booking()) {
				global $wpdb;
				// Attention! The use of wpdb->prepare() is not required
				// because the query does not use external data substitution
				$rows = $wpdb->get_results( "SELECT cl.calendar_id, cl.calendar_title, ct.category_name"
												. " FROM " . esc_sql($wpdb->prefix) . "booking_calendars AS cl"
												. " INNER JOIN " . esc_sql($wpdb->prefix) . "booking_categories AS ct ON cl.category_id=ct.category_id"
										);
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->calendar_id] = $row->calendar_title . ' (' . $row->category_name . ')';
					}
				}
			}
			$list = apply_filters('jacqueline_filter_list_booking_calendars', $list);
			if (jacqueline_get_theme_setting('use_list_cache')) jacqueline_storage_set('list_booking_calendars', $list); 
		}
		return $prepend_inherit ? jacqueline_array_merge(array('inherit' => esc_html__("Inherit", 'jacqueline')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('jacqueline_booking_reg_shortcodes')) {
	//Handler of add_filter('jacqueline_action_shortcodes_list',	'jacqueline_booking_reg_shortcodes');
	function jacqueline_booking_reg_shortcodes() {
		if (jacqueline_storage_isset('shortcodes')) {

			$booking_cats = jacqueline_get_list_booking_categories();
			$booking_cals = jacqueline_get_list_booking_calendars();

			jacqueline_sc_map('wp_booking_calendar', array(
				"title" => esc_html__("Booking Calendar", 'jacqueline'),
				"desc" => esc_html__("Insert Booking calendar", 'jacqueline'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"category_id" => array(
						"title" => esc_html__("Category", 'jacqueline'),
						"desc" => esc_html__("Select booking category", 'jacqueline'),
						"value" => "",
						"type" => "select",
						"options" => jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $booking_cats)
					),
					"calendar_id" => array(
						"title" => esc_html__("Calendar", 'jacqueline'),
						"desc" => esc_html__("or select booking calendar (id category is empty)", 'jacqueline'),
						"dependency" => array(
							'category_id' => array('empty', '0')
						),
						"value" => "",
						"type" => "select",
						"options" => jacqueline_array_merge(array(0 => esc_html__('- Select calendar -', 'jacqueline')), $booking_cals)
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('jacqueline_booking_reg_shortcodes_vc')) {
	//Handler of add_filter('jacqueline_action_shortcodes_list_vc',	'jacqueline_booking_reg_shortcodes_vc');
	function jacqueline_booking_reg_shortcodes_vc() {

		$booking_cats = jacqueline_get_list_booking_categories();
		$booking_cals = jacqueline_get_list_booking_calendars();


		// Jacqueline Donations form
		vc_map( array(
				"base" => "wp_booking_calendar",
				"name" => esc_html__("Booking Calendar", 'jacqueline'),
				"description" => esc_html__("Insert Booking calendar", 'jacqueline'),
				"category" => esc_html__('Content', 'jacqueline'),
				'icon' => 'icon_trx_booking',
				"class" => "trx_sc_single trx_sc_booking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "category_id",
						"heading" => esc_html__("Category", 'jacqueline'),
						"description" => esc_html__("Select Booking category", 'jacqueline'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $booking_cats)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "calendar_id",
						"heading" => esc_html__("Calendar", 'jacqueline'),
						"description" => esc_html__("Select Booking calendar", 'jacqueline'),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'category_id',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(jacqueline_array_merge(array(0 => esc_html__('- Select calendar -', 'jacqueline')), $booking_cals)),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Wp_Booking_Calendar extends Jacqueline_VC_ShortCodeSingle {}

	}
}
?>