<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_single_players_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_single_players_theme_setup', 1 );
	function jacqueline_template_single_players_theme_setup() {
		jacqueline_add_template(array(
			'layout' => 'single-players',
			'mode'   => 'players',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single Player', 'jacqueline'),
			'thumb_title'  => esc_html__('Large image (crop)', 'jacqueline'),
			'w'		 => 770,
			'h'		 => 434
		));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_single_players_output' ) ) {
	function jacqueline_template_single_players_output($post_options, $post_data) {
		$post_data['post_views']++;
		$show_title = jacqueline_get_custom_option('show_post_title')=='yes';
		$title_tag = jacqueline_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		jacqueline_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single_players'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/Article'
				. '">');

		if ($show_title && $post_options['location'] == 'center' && jacqueline_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="headline" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php jacqueline_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(jacqueline_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
                jacqueline_show_layout($post_options['dedicated']);
			} else {
				jacqueline_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php jacqueline_show_layout($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
		

		jacqueline_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' post_content_editor_present' : '').'" itemprop="articleBody">');

		if ($show_title && $post_options['location'] != 'center') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="name" class="post_title entry-title"><?php jacqueline_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}
		
		if(!empty($post_data['post_excerpt']) && ($post_options['location'] == 'left' || $post_options['location'] == 'right')){
            jacqueline_show_layout($post_data['post_excerpt']);
		}
		
		// Player information
		$post_meta = get_post_meta($post_data['post_id'], jacqueline_storage_get('options_prefix') . '_post_options', true);

		echo '<div class="player_info">'
				.(!empty($post_meta['player_country']) && $post_meta['player_country'] != 'inherit' ? '<span class="player_country">'. esc_html__( 'Country: ', 'jacqueline' ) .''. jacqueline_get_list_countries(false, $post_meta['player_country']) .'</span>' : '')
				.(!empty($post_meta['player_club']) && $post_meta['player_club'] != 'inherit' ? '<span class="player_club">'. esc_html__( 'Club: ', 'jacqueline' ) .''. trim($post_meta['player_club']) .'</span>' : '')
				.(!empty($post_meta['player_age']) && $post_meta['player_age'] != 'inherit' && $post_meta['player_type'] == 'player' ? '<span class="player_age">'. esc_html__( 'Age: ', 'jacqueline' ) .''. trim($post_meta['player_age']) .'</span>' : '')
			.'</div>';
			
		// Player socials
		if(!empty($post_meta['player_socials'])){ 
			$socials = $post_meta['player_socials'];
            jacqueline_show_layout(jacqueline_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($socials).'"][/trx_socials]'));
		}
			
		// Post content
		echo '<div class="player_content">';
			if ($post_data['post_protected']) {
                jacqueline_show_layout($post_data['post_excerpt']);
				echo get_the_password_form(); 
			} else {
                jacqueline_show_layout(jacqueline_gap_wrapper(jacqueline_reviews_wrapper($post_data['post_content'])));
				wp_link_pages( array( 
					'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'jacqueline' ) . '</span>', 
					'after' => '</nav>',
					'link_before' => '<span class="pager_numbers">',
					'link_after' => '</span>'
					)
				); 
			} 
		echo '</div>';

		// Prepare args for all rest template parts
		// This parts not pop args from storage!
		jacqueline_template_set_args('single-footer', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));
			
		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			get_template_part(jacqueline_get_file_slug('templates/_parts/editor-area.php'));
		}

		jacqueline_close_wrapper();
			
		if (!$post_data['post_protected']) {
			get_template_part(jacqueline_get_file_slug('templates/_parts/share.php'));
		}

		jacqueline_close_wrapper();

		if (!$post_data['post_protected']) {
			get_template_part(jacqueline_get_file_slug('templates/_parts/related-posts.php'));
			get_template_part(jacqueline_get_file_slug('templates/_parts/comments.php'));
		}

		// Manually pop args from storage
		// after all single footer templates
		jacqueline_template_get_args('single-footer');
	}
}
?>