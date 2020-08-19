<?php
/* Instagram Widget by WPZOOM support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_instagram_widget_by_wpzoom_theme_setup')) {
    add_action( 'jacqueline_action_before_init_theme', 'jacqueline_instagram_widget_by_wpzoom_theme_setup', 1 );
    function jacqueline_instagram_widget_by_wpzoom_theme_setup() {
        if (is_admin()) {
            add_filter( 'jacqueline_filter_required_plugins', 'jacqueline_instagram_widget_by_wpzoom_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'jacqueline_exists_instagram_widget_by_wpzoom' ) ) {
    function jacqueline_exists_instagram_widget_by_wpzoom() {
        return function_exists('zoom_instagram_widget_register');
    }
}


// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_instagram_widget_by_wpzoom_required_plugins' ) ) {
    function jacqueline_instagram_widget_by_wpzoom_required_plugins($list=array()) {
        if (in_array('instagram-widget-by-wpzoom', (array)jacqueline_storage_get('required_plugins')))
            $list[] = array(
                'name' 		=> esc_html__('Instagram Widget by WPZOOM', 'jacqueline'),
                'slug' 		=> 'instagram-widget-by-wpzoom',
                'required' 	=> false
            );
        return $list;
    }
}
