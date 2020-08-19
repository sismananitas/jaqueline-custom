<?php
/**
 * Jacqueline Framework: Admin functions
 *
 * @package	jacqueline
 * @since	jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Admin actions and filters:
------------------------------------------------------------------------ */

if (is_admin()) {

	/* Theme setup section
	-------------------------------------------------------------------- */
	
	if ( !function_exists( 'jacqueline_admin_theme_setup' ) ) {
		add_action( 'jacqueline_action_before_init_theme', 'jacqueline_admin_theme_setup', 11 );
		function jacqueline_admin_theme_setup() {
			if ( is_admin() ) {
				add_action("admin_footer",			'jacqueline_admin_prepare_scripts', 9);
				add_action("admin_enqueue_scripts",	'jacqueline_admin_load_scripts');
				add_action('tgmpa_register',		'jacqueline_admin_register_plugins');

				// AJAX: Get terms for specified post type
				add_action('wp_ajax_jacqueline_admin_change_post_type', 		'jacqueline_callback_admin_change_post_type');
				add_action('wp_ajax_nopriv_jacqueline_admin_change_post_type','jacqueline_callback_admin_change_post_type');
			}
		}
	}
	
	// Load required styles and scripts for admin mode
	if ( !function_exists( 'jacqueline_admin_load_scripts' ) ) {
		//Handler of add_action("admin_enqueue_scripts", 'jacqueline_admin_load_scripts');
		function jacqueline_admin_load_scripts() {
			if (jacqueline_get_theme_option('debug_mode')=='yes') {
				wp_enqueue_script( 'jacqueline-debug-script', jacqueline_get_file_url('js/core.debug.js'), array('jquery'), null, true );
			}
            wp_enqueue_style( 'jacqueline-admin-style', jacqueline_get_file_url('css/core.admin.css'), array(), null );
            wp_enqueue_script( 'jacqueline-admin-script', jacqueline_get_file_url('js/core.admin.js'), array('jquery'), null, true );
			if (jacqueline_strpos(add_query_arg(array()), 'widgets.php')!==false) {
                wp_enqueue_style( 'fontello-style', jacqueline_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null );
                wp_enqueue_style( 'jacqueline-animations-style', jacqueline_get_file_url('css/fontello-admin/css/animation.css'), array(), null );
			}
		}
	}
	
	// Prepare required styles and scripts for admin mode
	if ( !function_exists( 'jacqueline_admin_prepare_scripts' ) ) {
		//Handler of add_action("admin_footer", 'jacqueline_admin_prepare_scripts', 9);
		function jacqueline_admin_prepare_scripts() {
			$vars = jacqueline_storage_get('js_vars');
			if (empty($vars) || !is_array($vars)) $vars = array();
			$vars = array_merge($vars, array(
				'admin_mode' => true,
                'ajax_nonce' => wp_create_nonce(admin_url('admin-ajax.php')),
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_error' => esc_html__('Invalid server answer', 'jacqueline'),
                'importer_error_msg' => esc_html__('Errors that occurred during the import process:', 'jacqueline'),
                'msg_importer_full_alert' => esc_html__("ATTENTION!\n\nIn this case ALL THE OLD DATA WILL BE ERASED\nand YOU WILL GET A NEW SET OF POSTS, pages and menu items.", 'jacqueline')
					. "\n\n"
					. esc_html__("It is strongly recommended only for new installations of WordPress\n(without posts, pages and any other data)!", 'jacqueline')
					. "\n\n"
					. esc_html__("Press OK to continue or Cancel to return to a partial installation", 'jacqueline'),
				'user_logged_in' => true
			));
			wp_localize_script('jacqueline-admin-script', 'JACQUELINE_STORAGE', apply_filters('jacqueline_action_add_scripts_inline', $vars));
			$code = jacqueline_storage_get('js_code');
			if (!empty($js_code)) {
				$st = '<';
				$ct = '/';
				$et = '>';
				jacqueline_show_layout($code, "{$st}script{$et}jQuery(document).ready(function(){", "});{$st}{$ct}script{$et}");
			}
		}
	}
	
	// AJAX: Get terms for specified post type
	if ( !function_exists( 'jacqueline_callback_admin_change_post_type' ) ) {
		//Handler of add_action('wp_ajax_jacqueline_admin_change_post_type', 		'jacqueline_callback_admin_change_post_type');
		//Handler of add_action('wp_ajax_nopriv_jacqueline_admin_change_post_type',	'jacqueline_callback_admin_change_post_type');
		function jacqueline_callback_admin_change_post_type() {
			if ( !wp_verify_nonce( jacqueline_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
                wp_die();
			$post_type = sanitize_text_field($_REQUEST['post_type']);
			$terms = jacqueline_get_list_terms(false, jacqueline_get_taxonomy_categories_by_post_type($post_type));
			$terms = jacqueline_array_merge(array(0 => esc_html__('- Select category -', 'jacqueline')), $terms);
			$response = array(
				'error' => '',
				'data' => array(
					'ids' => array_keys($terms),
					'titles' => array_values($terms)
				)
			);
			echo json_encode($response);
            wp_die();
		}
	}

	// Return current post type in dashboard
	if ( !function_exists( 'jacqueline_admin_get_current_post_type' ) ) {
		function jacqueline_admin_get_current_post_type() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type )							//we have a post so we can just get the post type from that
				return $post->post_type;
			else if ( $typenow )										//check the global $typenow — set in admin.php
				return $typenow;
			else if ( $current_screen && $current_screen->post_type )	//check the global $current_screen object — set in sceen.php
				return $current_screen->post_type;
			else if ( isset( $_REQUEST['post_type'] ) )					//check the post_type querystring
				return sanitize_key( $_REQUEST['post_type'] );
			else if ( isset( $_REQUEST['post'] ) ) {					//lastly check the post id querystring
				$post = get_post( sanitize_key( $_REQUEST['post'] ) );
				return !empty($post->post_type) ? $post->post_type : '';
			} else														//we do not know the post type!
				return '';
		}
	}

	// Add admin menu pages
	if ( !function_exists( 'jacqueline_admin_add_menu_item' ) ) {
		function jacqueline_admin_add_menu_item($mode, $item, $pos='100') {
			static $shift = 0;
			if ($pos=='100') $pos .= '.'.$shift++;
			$fn = join('_', array('add', $mode, 'page'));
			if (empty($item['parent']))
				$fn($item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback'], $item['icon'], $pos);
			else
				$fn($item['parent'], $item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback'], $item['icon'], $pos);
		}
	}
	
	// Register optional plugins
	if ( !function_exists( 'jacqueline_admin_register_plugins' ) ) {
		function jacqueline_admin_register_plugins() {

			$plugins = apply_filters('jacqueline_filter_required_plugins', array());
			$config = array(
				'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',                      // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'parent_slug'  => 'themes.php',            // Parent menu slug.
				'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,                   // Automatically activate plugins after installation or not.
				'message'      => ''                       // Message to output right before the plugins table.
			);
	
			tgmpa( $plugins, $config );
		}
	}

	require_once JACQUELINE_FW_PATH . 'lib/tgm/class-tgm-plugin-activation.php';
}
