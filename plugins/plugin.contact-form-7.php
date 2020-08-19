<?php
/*Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_cf7_theme_setup')) {
    add_action( 'jacqueline_action_before_init_theme', 'jacqueline_cf7_theme_setup', 1 );
    function jacqueline_cf7_theme_setup() {
        if (is_admin()) {
            add_filter( 'jacqueline_filter_required_plugins', 'jacqueline_cf7_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'jacqueline_exists_cf7' ) ) {
    function jacqueline_exists_cf7() {
        return class_exists('WPCF7') && class_exists('WPCF7_ContactForm');
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_cf7_required_plugins' ) ) {
    function jacqueline_cf7_required_plugins($list=array()) {
        if (in_array('contact-form-7', (array)jacqueline_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Contact Form 7', 'jacqueline'),
                'slug'         => 'contact-form-7',
                'required'     => false
            );
        return $list;
    }
}