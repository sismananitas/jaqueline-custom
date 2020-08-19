<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_services_1_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_services_1_theme_setup', 1 );
	function jacqueline_template_services_1_theme_setup() {
		jacqueline_add_template(array(
			'layout' 		=> 'services-1',
			'template' 		=> 'services-1',
			'mode'   		=> 'services',
			'title'  		=> esc_html__('Services /Style 1/', 'jacqueline'),
			'thumb_title'   => esc_html__('Medium image services (crop)', 'jacqueline'),
			'w'		 		=> 358,
			'h'		 		=> 393
		));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_services_1_output' ) ) {
	function jacqueline_template_services_1_output($post_options, $post_data) {
		$show_title = !empty($post_data['post_title']);
		$parts 		= explode('_', $post_options['layout']);
		$style 		= $parts[0];
		$columns 	= max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));

		if (jacqueline_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?= esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div <?= !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_services_item sc_services_item_<?= esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?= (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
				. (!jacqueline_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<?php 
				if ($post_data['post_icon'] && $post_options['tag_type'] == 'icons'):
					$html = jacqueline_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]');
					if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
						?><?php jacqueline_show_layout($html); ?><?php
					} else
                        jacqueline_show_layout($html);
				else:
					?>
					<div class="sc_services_item_featured post_featured">
						<?php
						jacqueline_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(jacqueline_get_file_slug('templates/_parts/post-featured.php'));
						?>
					</div>
					<?php
				endif;
				?>
				<div class="sc_services_item_content">
					<?php if ($post_options['tag_type'] != 'icons'): ?>
						<div class="sc_services_item_category" style="pointer-events: none"><?= the_terms( $post_data['post_id'], 'services_group', '', ', ' ); ?></div>
					<?php endif; ?>
					<?php if ($show_title): ?>
						<h4 class="sc_services_item_title"><?php jacqueline_show_layout($post_data['post_title']); ?></h4>
						<!-- <h4 class="sc_services_item_title"><a href="<?= esc_url($post_data['post_link']); ?>"><?php jacqueline_show_layout($post_data['post_title']); ?></a></h4> -->
					<?php endif; ?>
					
					<div class="sc_services_item_description">
						<?php if ($post_data['post_protected']): ?>
                            <?= jacqueline_show_layout($post_data['post_excerpt']) ?>
						<?php else: ?>
							<?php if ($post_data['post_excerpt'] && $post_options['tag_type']=='icons'): ?>
								<?= in_array( $post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status') ) ? $post_data['post_excerpt'] : '<p>'
								.trim(jacqueline_strshort($post_data['post_excerpt'],
								isset($post_options['descr']) ? $post_options['descr'] : jacqueline_get_custom_option('post_excerpt_maxlength_masonry')))
								.'</p>' ?>
							<?php endif; ?>
							<?php if (!empty($post_data['post_link']) && !jacqueline_param_is_off($post_options['readmore'])): ?>
								<a href="<?php echo esc_url($post_data['post_link']); ?>" class="sc_services_item_readmore sc_button sc_button_style_filled sc_button_size_medium">
									<div><span class="first"><?php jacqueline_show_layout($post_options['readmore']); ?></span><span class="second"><?php jacqueline_show_layout($post_options['readmore']); ?></span></div>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		if (jacqueline_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}
	}
}
?>