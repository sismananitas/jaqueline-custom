<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jacqueline_gutenberg_theme_setup')) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_gutenberg_theme_setup', 1 );
	function jacqueline_gutenberg_theme_setup() {
        add_action( 'enqueue_block_editor_assets', 'jacqueline_gutenberg_editor_scripts' );
        if (is_admin()) {
			add_filter( 'jacqueline_filter_required_plugins', 'jacqueline_gutenberg_required_plugins' );
		}
	}
}

// Check if Gutenberg installed and activated
if ( !function_exists( 'jacqueline_exists_gutenberg' ) ) {
	function jacqueline_exists_gutenberg() {
		return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jacqueline_gutenberg_required_plugins' ) ) {
	//Handler of add_filter('jacqueline_filter_required_plugins',	'jacqueline_gutenberg_required_plugins');
	function jacqueline_gutenberg_required_plugins($list=array()) {
		if (in_array('gutenberg', (array)jacqueline_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Gutenberg', 'jacqueline'),
					'slug' 		=> 'gutenberg',
					'required' 	=> false
				);
		return $list;
	}
}


// Save CSS with custom colors and fonts to the gutenberg-editor-style.css
if ( ! function_exists( 'jacqueline_gutenberg_save_css' ) ) {
    add_action( 'jacqueline_action_compile_less', 'jacqueline_gutenberg_save_css', 99 );
    function jacqueline_gutenberg_save_css() {

        // Get main styles
        $css = jacqueline_fgc( jacqueline_get_file_dir( 'style.css' ) );

        // Append theme-vars styles
        $css .= jacqueline_fgc( jacqueline_get_file_dir( 'skins/less/skin.css' ) );

        if ( function_exists( 'trx_utils_css_add_context' ) ) {
            $css = str_replace('@charset "utf-8";', '', $css );
            $css = preg_replace('!/\*.*?\*/!s', '', $css);
            $css = preg_replace('/\n\s*\n/', "\n", $css);
            $css = trx_utils_css_add_context(
                $css,
                array(
                    'context' => '.edit-post-visual-editor ',
                    'context_self' => array( 'html', 'body', '.edit-post-visual-editor' )
                )
            );
        } else {
            $css = apply_filters( 'gutenberg_filter_prepare_css', $css );
        }



        // Save styles to the file
        jacqueline_fpc( jacqueline_get_file_dir( 'css/gutenberg-preview.css' ), $css );
    }
}
if (!function_exists('jacqueline_gutenberg_editor_scripts')) {
    function jacqueline_gutenberg_editor_scripts() {

        // Editor styles
        wp_enqueue_style( 'jacqueline-gutenberg-preview', jacqueline_get_file_url( 'css/gutenberg-preview.css' ), array(), null );

        //Editor scripts
        wp_enqueue_script( 'jacqueline-gutenberg-preview', jacqueline_get_file_url( 'js/gutenberg-preview.js' ), array( 'jquery' ), null, true );

        $body_scheme = jacqueline_get_custom_option('body_scheme');
        if (empty($body_scheme)  || jacqueline_is_inherit_option($body_scheme)) $body_scheme = 'original';

        wp_localize_script('jacqueline-gutenberg-preview','JACQUELINE_STORAGE', array(
            'color_scheme' => trim($body_scheme),
        ));
    }
}

