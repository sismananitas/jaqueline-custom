<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'jacqueline_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_theme_setup', 1 );

	function jacqueline_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text' => false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );

		// Register theme menus
		add_filter( 'jacqueline_filter_add_theme_menus', 'jacqueline_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'jacqueline_filter_add_theme_sidebars', 'jacqueline_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'jacqueline_filter_importer_options', 'jacqueline_set_importer_options' );

		// Add theme required plugins
		add_filter( 'jacqueline_filter_required_plugins', 'jacqueline_add_required_plugins' );

        // Add preloader styles
        add_filter('jacqueline_filter_add_styles_inline', 'jacqueline_head_add_page_preloader_styles');

		// Add tags to the head
		add_action('wp_head', 'jacqueline_head_add_page_meta', 1);
		
		// Add theme specified classes into the body
		add_filter( 'body_class', 'jacqueline_body_classes' );

        add_action('before', 'jacqueline_body_add_page_preloader');

		// Set list of the theme required plugins
		jacqueline_storage_set('required_plugins', array(
			'booked',
			'essgrids',
			'gdpr_framework',
            'instagram-widget-by-wpzoom',
			'revslider',
			'tribe_events',
			'trx_utils',
			'visual_composer',
			'woocommerce',
            'bookly-responsive-appointment-booking-tool',
            'contact-form-7',
            'mailchimp',
            'vc-extensions-bundle'
			)
		);

        // Gutenberg support
 		// Add wide and full blocks support
		add_theme_support( 'align-wide' );
        
        jacqueline_storage_set('demo_data_url',  esc_url(jacqueline_get_protocol().'://jacqueline.upd.themerex.net/demo') ); // Demo-site domain
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'jacqueline_add_theme_menus' ) ) {
	//Handler of add_filter( 'jacqueline_filter_add_theme_menus', 'jacqueline_add_theme_menus' );
	function jacqueline_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'jacqueline_add_theme_sidebars' ) ) {
	//Handler of add_filter( 'jacqueline_filter_add_theme_sidebars',	'jacqueline_add_theme_sidebars' );
	function jacqueline_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'jacqueline' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'jacqueline' )
			);
			if (function_exists('jacqueline_exists_woocommerce') && jacqueline_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'jacqueline' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'jacqueline_add_required_plugins' ) ) {
	// Handler of add_filter( 'jacqueline_filter_required_plugins', 'jacqueline_add_required_plugins' );
	function jacqueline_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('ThemeREX Utilities', 'jacqueline'),
			'version'	=> '3.4',
			'slug' 		=> 'trx_utils',
			'source'	=> jacqueline_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}

// Add theme required plugins
if ( !function_exists( 'jacqueline_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'jacqueline_add_trx_utils' );
    function jacqueline_add_trx_utils($enable=true) {
        return true;
    }
}

//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'jacqueline_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'jacqueline_importer_set_options', 9 );
    function jacqueline_importer_set_options( $options = array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;

            $wpml_slug = jacqueline_exists_wpml() ? '-wpml' : '';


            // Prepare demo data
            if ( is_dir( JACQUELINE_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = JACQUELINE_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( jacqueline_get_protocol().'://demofiles.themerex.net/jacqueline' . $wpml_slug ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] = array(
                'booked',
                'bookly',
                'essential-grid',
                'revslider',
                'the-events-calendar',
                'js_composer',
                'woocommerce',
                'sitepress-multilingual-cms',
                'contact-form-7',
                'mailchimp',
                'vc-extensions-bundle',
                'instagram-widget-by-wpzoom'
            );

            $options['theme_slug'] = 'jacqueline';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Jacqueline Demo', 'jacqueline' );
            $options['files']['default']['domain_dev'] = esc_url('http://jacqueline.upd.themerex.net'); // Developers domain
            $options['files']['default']['domain_demo'] = esc_url('http://jacqueline.themerex.net'); // Demo-site domain

        }
        return $options;
    }
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('jacqueline_body_classes') ) {
	//Handler of add_filter( 'body_class', 'jacqueline_body_classes' );
	function jacqueline_body_classes( $classes ) {

		$classes[] = 'jacqueline_body';
		$classes[] = 'body_style_' . trim(jacqueline_get_custom_option('body_style'));
		$classes[] = 'body_' . (jacqueline_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(jacqueline_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(jacqueline_get_custom_option('article_style'));
	
		$blog_style = jacqueline_get_custom_option(is_singular() && !jacqueline_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(jacqueline_get_template_name($blog_style));
	
		$body_scheme = jacqueline_get_custom_option('body_scheme');
		if (empty($body_scheme)  || jacqueline_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = jacqueline_get_custom_option('top_panel_position');
		if (!jacqueline_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = jacqueline_get_sidebar_class();

		if (jacqueline_get_custom_option('show_video_bg') == 'yes' && (jacqueline_get_custom_option('video_bg_youtube_code') != '' || jacqueline_get_custom_option('video_bg_url') != ''))
			$classes[] = 'video_bg_show';

		if (jacqueline_get_theme_option('page_preloader') != '')
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page preloader to the beginning of the body
if (!function_exists('jacqueline_body_add_page_preloader')) {
    //Handler of add_action('before', 'jacqueline_body_add_page_preloader');
    function jacqueline_body_add_page_preloader() {
        if ( ($preloader = jacqueline_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image = jacqueline_get_theme_option('page_preloader_image')) != '')) : ?>
			<div id="page_preloader">
				<?php if ($preloader == 'circle') : ?>
					<div class="preloader_wrap preloader_<?= esc_attr($preloader); ?>">
						<div class="preloader_circ1"></div>
						<div class="preloader_circ2"></div>
						<div class="preloader_circ3"></div>
						<div class="preloader_circ4"></div>
					</div>
				<?php elseif ($preloader == 'square') : ?>
					<div class="preloader_wrap preloader_<?= esc_attr($preloader); ?>">
						<div class="preloader_square1"></div>
						<div class="preloader_square2"></div>
					</div>
				<?php endif ?>
			</div>
		<?php endif;
    }
}

// Add page preloader styles to the head
if (!function_exists('jacqueline_head_add_page_preloader_styles')) {
    //Handler of add_filter('jacqueline_filter_add_styles_inline', 'jacqueline_head_add_page_preloader_styles');
    function jacqueline_head_add_page_preloader_styles($css) {
        if (($preloader=jacqueline_get_theme_option('page_preloader'))!='none') {
            $image = jacqueline_get_theme_option('page_preloader_image');
            $bg_clr = jacqueline_get_theme_option('page_preloader_bg_color');
            $link_clr = jacqueline_get_theme_option('page_preloader_text_color');
            $css .= '
				body #page_preloader {
					background-color: '. esc_attr($bg_clr) . '!important;'
                . ($preloader=='custom' && $image
                    ? 'background-image:url('.esc_url($image).');'
                    : ''
                )
                . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
        }
        return $css;
    }
}

// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'jacqueline_trx_utils_privacy_text' ) ) {
    add_filter( 'trx_utils_filter_privacy_text', 'jacqueline_trx_utils_privacy_text' );
    function jacqueline_trx_utils_privacy_text( $text = '' ) {
        return jacqueline_get_privacy_text();
    }
}

// Add page meta to the head
if (!function_exists('jacqueline_head_add_page_meta')) {
	// Handler of add_action('wp_head', 'jacqueline_head_add_page_meta', 1);
	function jacqueline_head_add_page_meta() {
		?>
			<meta charset="<?php bloginfo( 'charset' ); ?>" />
			<meta name="viewport" content="width=device-width, initial-scale=1<?php if (jacqueline_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
			<meta name="format-detection" content="telephone=no">

			<link rel="profile" href="http://gmpg.org/xfn/11" />
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';

/**
 * JESÚS ADAME
 */
//add_action("wp_enqueue_scripts", "dcms_insertar_js");

function dcms_insertar_js() {
	if (is_page('pruebas')) {
		//wp_register_script('miscript', 'https://gateway-na.americanexpress.com/checkout/version/56/checkout.js', array('jquery'), '1', true );
		//wp_enqueue_script('miscript');
		
		//wp_register_script('miscript2', get_stylesheet_directory_uri(). '/js/index.js', array('jquery'), '2', true );
		//wp_enqueue_script('miscript2');
	} else {
		//echo "<script>alert('No es la página')</script>";
	}
}