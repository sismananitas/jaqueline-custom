<?php
/*  Bookly Lite
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_bookly_responsive_appointment_booking_tool_theme_setup')) {
    add_action( 'jacqueline_action_before_init_theme', 'jacqueline_bookly_responsive_appointment_booking_tool_theme_setup', 1 );
    function jacqueline_bookly_responsive_appointment_booking_tool_theme_setup() {
        // Register shortcode in the shortcodes list
        if (is_admin()) {
            add_filter( 'jacqueline_filter_required_plugins',				'jacqueline_bookly_responsive_appointment_booking_tool_required_plugins' );
        }
    }
}


// Check if plugin installed and activated
if ( !function_exists( 'jacqueline_exists_bookly_responsive_appointment_booking_tool' ) ) {
    function jacqueline_exists_bookly_responsive_appointment_booking_tool() {
        return function_exists('bookly_loader');
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_bookly_responsive_appointment_booking_tool_required_plugins' ) ) {
    //Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_bookly_responsive_appointment_booking_tool_required_plugins');
    function jacqueline_bookly_responsive_appointment_booking_tool_required_plugins($list=array()) {
        if (in_array('bookly-responsive-appointment-booking-tool', (array)jacqueline_storage_get('required_plugins'))) {
            $path = jacqueline_get_file_dir('plugins/install/bookly-responsive-appointment-booking-tool.zip');
            if (!empty($path) && file_exists($path)) {
                $list[] = array(
                    'name' 		=> esc_html__('Bookly Lite', 'jacqueline'),
                    'slug' 		=> 'bookly-responsive-appointment-booking-tool',
                    'source'	=> $path,
                    'required' 	=> false,
                    'version'   => '17.5'
                );
            }

        }
        return $list;
    }
}