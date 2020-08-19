<?php
/**
 * The Header for our theme.
 */

// Theme init - don't remove next row! Load custom options
jacqueline_core_init_theme();
jacqueline_profiler_add_point(esc_html__('Before Theme HTML output', 'jacqueline'));

?>
<!DOCTYPE html>
<html
	<?php language_attributes(); ?>
	class="<?php 
		$body_scheme = jacqueline_get_custom_option('body_scheme');
		if (empty($body_scheme)  || jacqueline_is_inherit_option($body_scheme)) $body_scheme = 'original';
		echo 'scheme_' . esc_attr($body_scheme);
	?>"
>
<head>
	<?php wp_head(); ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-155191796-2"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-155191796-2');
	</script>
</head>

<body <?php body_class();?>>
	<?php 
		jacqueline_profiler_add_point(esc_html__('BODY start', 'jacqueline'));
		jacqueline_show_layout(jacqueline_get_custom_option('gtm_code'));
		do_action( 'before' );
	?>

	<?php if ( !jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_outer')) ): ?>
		<div class="outer_wrap">
	<?php endif; ?>

	<?php get_template_part(jacqueline_get_file_slug('sidebar_outer.php')); ?>
	<?php
		$body_style  = jacqueline_get_custom_option('body_style');
		$class = $style = '';

		if (jacqueline_get_custom_option('bg_custom') == 'yes' && ($body_style == 'boxed' || jacqueline_get_custom_option('bg_image_load') == 'always')):
			if (($img = jacqueline_get_custom_option('bg_image_custom')) != '')
				$style = 'background: url('. esc_url($img) .') '. str_replace('_', ' ', jacqueline_get_custom_option('bg_image_custom_position')) .' no-repeat fixed;';
			else if (($img = jacqueline_get_custom_option('bg_pattern_custom')) != '')
				$style = 'background: url('. esc_url($img) .') 0 0 repeat fixed;';
			else if (($img = jacqueline_get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'. ($img);
			else if (($img = jacqueline_get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'. ($img);
			if (($img = jacqueline_get_custom_option('bg_color')) != '')
				$style .= 'background-color: '. ($img) .';';
		endif;
	?>
	<div class="body_wrap<?= !empty($class) ? ' '. esc_attr($class) : ''; ?>"<?= !empty($style) ? ' style="'. esc_attr($style) .'"' : ''; ?>>
		<div class="page_wrap">
			<?php
				$top_panel_style = jacqueline_get_custom_option('top_panel_style');
				$top_panel_position = jacqueline_get_custom_option('top_panel_position');
				$top_panel_scheme = jacqueline_get_custom_option('top_panel_scheme');

				jacqueline_profiler_add_point(esc_html__('Before Page Header', 'jacqueline'));
				// Top panel 'Above' or 'Over'
				if (in_array($top_panel_position, array('above', 'over'))):
					jacqueline_storage_set('header_mobile', array( 
						'open_hours' 	  => false, 
						'login' 		  => false, 
						'socials' 		  => false, 
						'bookmarks' 	  => false, 
						'contact_address' => false, 
						'contact_phone'   => false, 
						'contact_email'   => false, 
						'woo_cart' 		  => true, 
						'search' 		  => true 
					)); 

					// Mobile Menu
					get_template_part(jacqueline_get_file_slug('templates/headers/_parts/header-mobile.php'));
					
					jacqueline_show_post_layout(array(
						'layout'   => $top_panel_style,
						'position' => $top_panel_position,
						'scheme'   => $top_panel_scheme
					), false);

					jacqueline_profiler_add_point(esc_html__('After show menu', 'jacqueline'));
				endif;

				// Slider
				get_template_part(jacqueline_get_file_slug('templates/headers/_parts/slider.php'));
				
				// Top panel 'Below'
				if ($top_panel_position == 'below'):
					jacqueline_show_post_layout(array(
						'layout'   => $top_panel_style,
						'position' => $top_panel_position,
						'scheme'   => $top_panel_scheme
					), false);

					// Mobile Menu
					get_template_part(jacqueline_get_file_slug('templates/headers/_parts/header-mobile.php'));
					jacqueline_profiler_add_point(esc_html__('After show menu', 'jacqueline'));
				endif;

				// Top of page section: page title and breadcrumbs
				get_template_part(jacqueline_get_file_slug('templates/headers/_parts/breadcrumbs.php'));
			?>
			<div class="page_content_wrap page_paddings_<?= esc_attr(jacqueline_get_custom_option('body_paddings')); ?>">
				<?php
					jacqueline_profiler_add_point(esc_html__('Before Page content', 'jacqueline'));
					// Content and sidebar wrapper
					if ($body_style != 'fullscreen') jacqueline_open_wrapper('<div class="content_wrap">');
					
					// Main content wrapper
					jacqueline_open_wrapper('<div class="content">');
					$cat_descr = category_description();
				?>

				<?php if ($cat_descr): ?>
					<div><p><?= category_description(); ?></p></div>
				<?php endif; ?>