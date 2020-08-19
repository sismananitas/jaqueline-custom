<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('jacqueline_action_skin_theme_setup')) {
	add_action( 'jacqueline_action_init_theme', 'jacqueline_action_skin_theme_setup', 1 );
	function jacqueline_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('jacqueline_filter_used_fonts',			'jacqueline_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('jacqueline_filter_list_fonts',			'jacqueline_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('jacqueline_action_add_styles',			'jacqueline_action_skin_add_styles');
		// Add skin inline styles
		add_filter('jacqueline_filter_add_styles_inline',		'jacqueline_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('jacqueline_action_add_responsive',		'jacqueline_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('jacqueline_filter_add_responsive_inline',	'jacqueline_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('jacqueline_action_add_scripts',			'jacqueline_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('jacqueline_action_add_scripts_inline',	'jacqueline_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('jacqueline_filter_compile_less',			'jacqueline_filter_skin_compile_less');



		// Add color schemes
		jacqueline_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'jacqueline'),

			// Accent colors
			'accent1'				=> '#F9A392',
			'accent1_hover'			=> '#8ED4CC',
			
			// Headers, text and links colors
			'text'					=> '#757575',
			'text_light'			=> '#9A9A9A',
			'text_dark'				=> '#323232',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#B2B2B2',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#8ED4CC',
			
			// Whole block border and background
			'bd_color'				=> '#F2F2F2',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#757575',
			'alter_light'			=> '#9A9A9A',
			'alter_dark'			=> '#232A34',
			'alter_link'			=> '#F9A392',
			'alter_hover'			=> '#8ED4CC',
			'alter_bd_color'		=> '#F4F4F4',
			'alter_bd_hover'		=> '#9A9A9A',
			'alter_bg_color'		=> '#F4F4F4',
			'alter_bg_hover'		=> '#F4F4F4',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		jacqueline_add_color_scheme('light', array(

			'title'					=> esc_html__('Light', 'jacqueline'),

			// Accent colors
			'accent1'				=> '#20C7CA',
			'accent1_hover'			=> '#189799',
			
			// Headers, text and links colors
			'text'					=> '#ffffff',
			'text_light'			=> '#ffffff',
			'text_dark'				=> '#ffffff',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dddddd',
			'bg_color'				=> '#8ED4CC',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#e7e7e7',
			'alter_bd_hover'		=> '#dddddd',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		jacqueline_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'jacqueline'),

			// Accent colors
			'accent1'				=> '#F9A392',
			'accent1_hover'			=> '#8ED4CC',
			
			// Headers, text and links colors
			'text'					=> '#757575',
			'text_light'			=> '#9A9A9A',
			'text_dark'				=> '#323232',
			'inverse_text'			=> '#FFFFFF',
			'inverse_light'			=> '#B2B2B2',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#8ED4CC',
			
			// Whole block border and background
			'bd_color'				=> 'rgba(242, 242, 242, 0.6)',
			'bg_color'				=> '#FFFFFF',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#757575',
			'alter_light'			=> '#9A9A9A',
			'alter_dark'			=> '#FFFFFF',
			'alter_link'			=> '#F9A392',
			'alter_hover'			=> '#8ED4CC',
			'alter_bd_color'		=> '#F4F4F4',
			'alter_bd_hover'		=> '#9A9A9A',
			'alter_bg_color'		=> '#F4F4F4',
			'alter_bg_hover'		=> '#F4F4F4',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);


		// Add Custom fonts
		jacqueline_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '2.92em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '2.153em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.54em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.385em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.077em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Droid Serif',
			'font-size' 	=> '1.077em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.5em'
			)
		);
		jacqueline_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Droid Serif',
			'font-size' 	=> '13px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.92em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		jacqueline_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		jacqueline_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '',
			'font-style'	=> 'i',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		jacqueline_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '2.3em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '1.6em'
			)
		);
		jacqueline_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '2.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		jacqueline_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '2em'
			)
		);
		jacqueline_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '11px',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '2em'
			)
		);
		jacqueline_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'jacqueline'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '13px',
			'font-weight'	=> '',
			'font-style'	=> 'i',
			'line-height'	=> '1.3em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('jacqueline_filter_skin_used_fonts')) {
	//Handler of add_filter('jacqueline_filter_used_fonts', 'jacqueline_filter_skin_used_fonts');
	function jacqueline_filter_skin_used_fonts($theme_fonts) {
		// For example: $theme_fonts['Roboto'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
if (!function_exists('jacqueline_filter_skin_list_fonts')) {
	//Handler of add_filter('jacqueline_filter_list_fonts', 'jacqueline_filter_skin_list_fonts');
	function jacqueline_filter_skin_list_fonts($list) {
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('jacqueline_action_skin_add_styles')) {
	//Handler of add_action('jacqueline_action_add_styles', 'jacqueline_action_skin_add_styles');
	function jacqueline_action_skin_add_styles() {
		// Add stylesheet files
        wp_enqueue_style( 'jacqueline-skin-style', jacqueline_get_file_url('skins/less/skin.css'), array(), null );
		if (file_exists(jacqueline_get_file_dir('skin.customizer.css')))
            wp_enqueue_style( 'jacqueline-skin-customizer-style', jacqueline_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('jacqueline_filter_skin_add_styles_inline')) {
	//Handler of add_filter('jacqueline_filter_add_styles_inline', 'jacqueline_filter_skin_add_styles_inline');
	function jacqueline_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('jacqueline_action_skin_add_responsive')) {
	//Handler of add_action('jacqueline_action_add_responsive', 'jacqueline_action_skin_add_responsive');
	function jacqueline_action_skin_add_responsive() {
		$suffix = jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(jacqueline_get_file_dir('skin.responsive'.($suffix).'.css')))
            wp_enqueue_style( 'theme-skin-responsive-style', jacqueline_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('jacqueline_filter_skin_add_responsive_inline')) {
	//Handler of add_filter('jacqueline_filter_add_responsive_inline', 'jacqueline_filter_skin_add_responsive_inline');
	function jacqueline_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('jacqueline_filter_skin_compile_less')) {
	//Handler of add_filter('jacqueline_filter_compile_less', 'jacqueline_filter_skin_compile_less');
	function jacqueline_filter_skin_compile_less($files) {
		if (file_exists(jacqueline_get_file_dir('skins/less/skin.less'))) {
		 	$files[] = jacqueline_get_file_dir('skins/less/skin.less');
		}
		return $files;
    }
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('jacqueline_action_skin_add_scripts')) {
	//Handler of add_action('jacqueline_action_add_scripts', 'jacqueline_action_skin_add_scripts');
	function jacqueline_action_skin_add_scripts() {
		if (file_exists(jacqueline_get_file_dir('skin.js')))
            wp_enqueue_script( 'theme-skin-script', jacqueline_get_file_url('skin.js'), array(), null );
		if (jacqueline_get_theme_option('show_theme_customizer') == 'yes' && file_exists(jacqueline_get_file_dir('skin.customizer.js')))
            wp_enqueue_script( 'theme-skin-customizer-script', jacqueline_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('jacqueline_action_skin_add_scripts_inline')) {
	//Handler of add_filter('jacqueline_action_add_scripts_inline', 'jacqueline_action_skin_add_scripts_inline');
	function jacqueline_action_skin_add_scripts_inline($vars=array()) {
		// Todo: add skin specific script's vars
		return $vars;
	}
}
?>