<?php
/**
 * Single post
 */
get_header(); 

$single_style = jacqueline_storage_get('single_style');
if (empty($single_style)) $single_style = jacqueline_get_custom_option('single_style');

?>
<?php while ( have_posts() ) : ?>
	<?php the_post() ?>

	<?php
		jacqueline_show_post_layout([
			'layout'  	 => $single_style,
			'sidebar' 	 => !jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_main')),
			'content' 	 => jacqueline_get_template_property($single_style, 'need_content'),
			'terms_list' => jacqueline_get_template_property($single_style, 'need_terms')
		]);
	?>
<?php endwhile;

get_footer();
?>