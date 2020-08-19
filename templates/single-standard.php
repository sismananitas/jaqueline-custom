<?php

// Disable direct call
if (!defined( 'ABSPATH' )) { exit; }

/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists( 'jacqueline_template_single_standard_theme_setup' )) :
	add_action('jacqueline_action_before_init_theme', 'jacqueline_template_single_standard_theme_setup', 1);

	function jacqueline_template_single_standard_theme_setup() {
		jacqueline_add_template([
			'layout' 		=> 'single-standard',
			'mode'   		=> 'single',
			'need_content' 	=> true,
			'need_terms' 	=> true,
			'title'  		=> esc_html__('Single standard', 'jacqueline'),
			'thumb_title'  	=> esc_html__('Fullwidth image (crop)', 'jacqueline'),
			'w'		 		=> 1170,
			'h'		 		=> 659
		]);
	}
endif;

?>

<?php if (!function_exists( 'jacqueline_template_single_standard_output' )) : ?>
	<?php function jacqueline_template_single_standard_output($post_options, $post_data) { ?>
		<?php
			// Template output
			$post_data['post_views']++;
			$avg_author = 0;
			$avg_users  = 0;

			if (!$post_data['post_protected'] && $post_options['reviews'] && jacqueline_get_custom_option('show_reviews') == 'yes') :
				$avg_author = $post_data['post_reviews_author'];
				$avg_users  = $post_data['post_reviews_users'];
			endif;

			$show_title = jacqueline_get_custom_option('show_post_title') == 'yes'
			&& (jacqueline_get_custom_option('show_post_title_on_quotes') == 'yes'
			|| !in_array($post_data['post_format'], [ 'aside', 'chat', 'status', 'link', 'quote' ]));

			$title_tag = jacqueline_get_custom_option('show_page_title') == 'yes' ? 'h3' : 'h1';

			jacqueline_open_wrapper('<article class="' 
			. join(' ', get_post_class('itemscope'
				. ' post_item post_item_single'
				. ' post_featured_' . esc_attr($post_options['post_class'])
				. ' post_format_' . esc_attr($post_data['post_format'])))
			. '"'
			. ' itemscope itemtype="http://schema.org/'. ($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
			. '">');
		?>

		<?php if ($show_title && $post_options['location'] == 'center' && jacqueline_get_custom_option('show_page_title') == 'no') : ?>
			<<?= esc_html($title_tag); ?> itemprop="<?= (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title">
				<span class="post_icon <?= esc_attr($post_data['post_icon']); ?>"></span>
				<?php jacqueline_show_layout($post_data['post_title']); ?>
			</<?= esc_html($title_tag); ?>>
		<?php endif ?>

		<?php if (!$post_data['post_protected'] && (!empty($post_options['dedicated']) || (jacqueline_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb']))): ?>
			<section class="post_featured">
				<?php if (!empty($post_options['dedicated'])) : ?>
					<?php jacqueline_show_layout($post_options['dedicated']); ?>
				<?php else : ?>
					<?php jacqueline_enqueue_popup(); ?>
					<div class="post_thumb" data-image="<?= esc_url($post_data['post_attachment']); ?>" data-title="<?= esc_attr($post_data['post_title']); ?>">
						<a class="hover_icon hover_icon_view" href="<?= esc_url($post_data['post_attachment']); ?>" title="<?= esc_attr($post_data['post_title']); ?>">
							<?php jacqueline_show_layout($post_data['post_thumb']); ?>
						</a>
					</div>
				<?php endif ?>
			</section>
		<?php endif ?>

		<?php
			jacqueline_open_wrapper(
				'<section class="post_content'
				.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' post_content_editor_present' : '')
				.'" itemprop="'. ($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody') .'">'
			);
		?>

		<?php if ($show_title && $post_options['location'] != 'center' && jacqueline_get_custom_option('show_page_title') == 'no') : ?>
			<<?= esc_html($title_tag); ?> itemprop="<?= (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title">
				<span class="post_icon <?= esc_attr($post_data['post_icon']); ?>"></span> <?php jacqueline_show_layout($post_data['post_title']); ?>
			</<?= esc_html($title_tag); ?>>
		<?php endif ?>

		<?php if (!$post_data['post_protected'] && jacqueline_get_custom_option('show_post_info') == 'yes'): ?>
			<?php
				$post_options['info_parts'] = ['snippets' => true];
				jacqueline_template_set_args('post-info', array(
					'post_options' => $post_options,
					'post_data'    => $post_data
				));
				get_template_part(jacqueline_get_file_slug('templates/_parts/post-info.php'));
			?>
		<?php endif ?>
		
		<?php
			jacqueline_template_set_args('reviews-block', [
				'post_options' 	=> $post_options,
				'post_data'		=> $post_data,
				'avg_author' 	=> $avg_author,
				'avg_users' 	=> $avg_users
			]);
			get_template_part(jacqueline_get_file_slug('templates/_parts/reviews-block.php'));
		?>
		<!-- Post content -->
		<?php if ($post_data['post_protected']): ?>
			<?php jacqueline_show_layout($post_data['post_excerpt']); ?>
			<?= get_the_password_form(); ?>
		<?php else: ?>
			<?php
				if (
					function_exists('jacqueline_sc_reviews') &&
					!jacqueline_storage_empty('reviews_markup') &&
					jacqueline_strpos($post_data['post_content'], jacqueline_get_reviews_placeholder()) === false
				) 
					$post_data['post_content'] = jacqueline_sc_reviews([]).($post_data['post_content']);
					
				jacqueline_show_layout(jacqueline_gap_wrapper(jacqueline_reviews_wrapper($post_data['post_content'])));
				wp_link_pages(array( 
						'before' 	  => '<nav class="pagination_single"><span class="pager_pages">'. esc_html__( 'Pages:', 'jacqueline' ) .'</span>', 
						'after'  	  => '</nav>',
						'link_before' => '<span class="pager_numbers">',
						'link_after'  => '</span>'
					)
				); 
			?>
			<?php if (jacqueline_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)): ?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags">
						<?php esc_html_e('Tags:', 'jacqueline'); ?> <?= join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?>
					</span>
				</div>
			<?php endif ?>
		<?php endif ?>

		<?php
			// Prepare args for all rest template parts
			// This parts not pop args from storage!
			jacqueline_template_set_args('single-footer', array(
				'post_options' => $post_options,
				'post_data'	   => $post_data
			));

			if (!$post_data['post_protected'] && $post_data['post_edit_enable']) :
				get_template_part(jacqueline_get_file_slug('templates/_parts/editor-area.php'));
			endif;
				
			jacqueline_close_wrapper();
				
			if (!$post_data['post_protected']) :
				get_template_part(jacqueline_get_file_slug('templates/_parts/author-info.php'));
				get_template_part(jacqueline_get_file_slug('templates/_parts/share.php'));
			endif;

			$sidebar_present = !jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_main'));

			// if (!$sidebar_present) jacqueline_close_wrapper();
			// get_template_part(jacqueline_get_file_slug('templates/_parts/related-posts.php'));
			if ($sidebar_present) jacqueline_close_wrapper();

			if (!$post_data['post_protected']) :
				get_template_part(jacqueline_get_file_slug('templates/_parts/comments.php'));
			endif;

			// Manually pop args from storage
			// after all single footer templates
			jacqueline_template_get_args('single-footer');
		?>
	<?php }	?>
<?php endif ?>