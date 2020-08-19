<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_services_2_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_services_2_theme_setup', 1 );
	function jacqueline_template_services_2_theme_setup() {
		jacqueline_add_template(array(
			'layout' => 'services-2',
			'template' => 'services-2',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 2/', 'jacqueline'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'jacqueline'),
			'w'		 => 370,
			'h'		 => 370
		));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_services_2_output' ) ):
	function jacqueline_template_services_2_output($post_options, $post_data) {
		$show_title = !empty($post_data['post_title']);
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (jacqueline_param_is_on($post_options['slider'])): ?>
			<div class="swiper-slide" data-style="<?= esc_attr($post_options['tag_css_wh']); ?>" style="<?= esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap">
		<?php
		elseif ($columns > 1): ?><div class="column-1_<?= esc_attr($columns); ?> column_padding_bottom"><?php endif	?>
			<div
				<?= !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_services_item sc_services_item_<?= esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?= (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
				. (!jacqueline_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(jacqueline_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>
			>
				<?php if ($post_data['post_icon'] && $post_options['tag_type']=='icons'): ?>
					<?php $html = jacqueline_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]');	?>
					<?= jacqueline_show_layout($html); ?>
				<?php else: ?>
					<div class="sc_services_item_featured post_featured">
						<?php
						jacqueline_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(jacqueline_get_file_slug('templates/_parts/post-featured.php'));
						?>
					</div>
				<?php endif ?>
				<div class="sc_services_item_content">
					<?php if ($show_title): ?>
						<?php if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])): ?>
							<h4 class="sc_services_item_title" style="cursor: default"><?php jacqueline_show_layout($post_data['post_title']); ?></h4>
						<?php else: ?>
							<h4 class="sc_services_item_title" style="cursor: default"><?php jacqueline_show_layout($post_data['post_title']); ?></h4>
						<?php endif ?>
					<?php endif ?>

					<div class="sc_services_item_description">
						<?php if ($post_data['post_protected']):
                            jacqueline_show_layout($post_data['post_excerpt']); ?>
						<?php else:
							if ($post_data['post_excerpt']):
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(jacqueline_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : jacqueline_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							endif; ?>
						<?php endif ?>
					</div>
				</div>
			</div>
		<?php
		if (jacqueline_param_is_on($post_options['slider'])) :
			?></div>
		</div><?php
		elseif ($columns > 1):
			?></div><?php
		endif;
	}
endif
?>