<?php
/**
 * Jacqueline Framework
 *
 * @package jacqueline
 * @since jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'JACQUELINE_FW_DIR' ) )		define( 'JACQUELINE_FW_DIR',		'fw' );
if ( ! defined( 'JACQUELINE_THEME_PATH' ) )	define( 'JACQUELINE_THEME_PATH',	trailingslashit( get_template_directory() ) );
if ( ! defined( 'JACQUELINE_FW_PATH' ) )		define( 'JACQUELINE_FW_PATH',		JACQUELINE_THEME_PATH . JACQUELINE_FW_DIR . '/' );

// Include theme variables storage
require_once JACQUELINE_FW_PATH . 'core/core.storage.php';

// Theme variables storage
jacqueline_storage_set('options_prefix', 'jacqueline');
jacqueline_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
jacqueline_storage_set('widgets_args', array(			// Arguments to register widgets
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h5 class="widget_title">',
        'after_title'   => '</h5>',
    )
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'jacqueline_loader_theme_setup' ) ) {
    add_action( 'after_setup_theme', 'jacqueline_loader_theme_setup', 20 );
    function jacqueline_loader_theme_setup() {

        // Before init theme
        do_action('jacqueline_action_before_init_theme');

        // Load current values for main theme options
        jacqueline_load_main_options();

        // Theme core init - only for admin side. In frontend it called from action 'wp'
        if ( is_admin() ) {
            jacqueline_core_init_theme();
        }
    }
}


/* Include core parts
------------------------------------------------------------------------ */

// String utilities. core.strings must be first - we use jacqueline_str...() in the jacqueline_get_file_dir()
require_once JACQUELINE_FW_PATH . 'core/core.strings.php';
// File utilities. core.files must be first - we use jacqueline_get_file_dir() to include all rest parts
require_once JACQUELINE_FW_PATH . 'core/core.files.php';
// Debug utilities
require_once JACQUELINE_FW_PATH . 'core/core.debug.php';

// Include custom theme files
require_once JACQUELINE_THEME_PATH . 'includes/theme.options.php';
require_once JACQUELINE_THEME_PATH . 'includes/theme.shortcodes.php';
require_once JACQUELINE_THEME_PATH . 'skins/less/skin.php';

// Include core files
require_once JACQUELINE_FW_PATH . 'core/core.admin.php';
require_once JACQUELINE_FW_PATH . 'core/core.arrays.php';
require_once JACQUELINE_FW_PATH . 'core/core.date.php';
require_once JACQUELINE_FW_PATH . 'core/core.html.php';
require_once JACQUELINE_FW_PATH . 'core/core.http.php';
require_once JACQUELINE_FW_PATH . 'core/core.ini.php';
require_once JACQUELINE_FW_PATH . 'core/core.less.php';
require_once JACQUELINE_FW_PATH . 'core/core.lists.php';
require_once JACQUELINE_FW_PATH . 'core/core.media.php';
require_once JACQUELINE_FW_PATH . 'core/core.messages.php';
require_once JACQUELINE_FW_PATH . 'core/core.reviews.php';
require_once JACQUELINE_FW_PATH . 'core/core.storage.php';
require_once JACQUELINE_FW_PATH . 'core/core.templates.php';
require_once JACQUELINE_FW_PATH . 'core/core.theme.php';
require_once JACQUELINE_FW_PATH . 'core/core.wp.php';
require_once JACQUELINE_FW_PATH . 'core/support.attachment.php';
require_once JACQUELINE_FW_PATH . 'core/support.post.php';
require_once JACQUELINE_FW_PATH . 'core/support.post_type.php';
require_once JACQUELINE_FW_PATH . 'core/support.taxonomy.php';
require_once JACQUELINE_FW_PATH . 'core/core.customizer/core.customizer.php';
require_once JACQUELINE_FW_PATH . 'core/core.options/core.options.php';

// Include theme-specific plugins and post types
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.booked.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.booking.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.essgrids.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.gdpr-framework.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.gutenberg.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.instagram-widget-by-wpzoom.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.mailchimp.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.revslider.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.tribe-events.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.visual-composer.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.woocommerce.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.wpml.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.bookly-responsive-appointment-booking-tool.php';
require_once JACQUELINE_THEME_PATH . 'plugins/plugin.contact-form-7.php';
require_once JACQUELINE_THEME_PATH . 'plugins/support.clients.php';
require_once JACQUELINE_THEME_PATH . 'plugins/support.menuitems.php';
require_once JACQUELINE_THEME_PATH . 'plugins/support.services.php';
require_once JACQUELINE_THEME_PATH . 'plugins/support.team.php';
require_once JACQUELINE_THEME_PATH . 'plugins/support.testimonials.php';

// Include theme templates.
// Using get_template_part(), because templates can be replaced in the child theme
get_template_part('templates/404');
get_template_part('templates/attachment');
get_template_part('templates/excerpt');
get_template_part('templates/gallery');
get_template_part('templates/masonry');
get_template_part('templates/no-articles');
get_template_part('templates/no-search');
get_template_part('templates/portfolio');
get_template_part('templates/related');
get_template_part('templates/single-matches');
get_template_part('templates/single-players');
get_template_part('templates/single-portfolio');
get_template_part('templates/single-standard');
get_template_part('templates/single-team');
get_template_part('templates/headers/header_3');
get_template_part('templates/headers/header_4');
get_template_part('templates/headers/header_6');
get_template_part('templates/headers/header_7');
get_template_part('templates/trx_blogger/accordion');
get_template_part('templates/trx_blogger/date');
get_template_part('templates/trx_blogger/list');
get_template_part('templates/trx_blogger/plain');
get_template_part('templates/trx_blogger/polaroid');
get_template_part('templates/trx_clients/clients-1');
get_template_part('templates/trx_events/events-1');
get_template_part('templates/trx_events/events-2');
get_template_part('templates/trx_form/form_1');
get_template_part('templates/trx_form/form_2');
get_template_part('templates/trx_form/form_3');
get_template_part('templates/trx_form/form_custom');
get_template_part('templates/trx_menuitems/menuitems-1');
get_template_part('templates/trx_menuitems/menuitems-2');
get_template_part('templates/trx_services/services-1');
get_template_part('templates/trx_services/services-2');
get_template_part('templates/trx_team/team-3');
get_template_part('templates/trx_testimonials/testimonials-1');
get_template_part('templates/trx_testimonials/testimonials-2');
?>