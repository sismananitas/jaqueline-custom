<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_excerpt_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_excerpt_theme_setup', 1 );
	function jacqueline_template_excerpt_theme_setup() {
		jacqueline_add_template(array(
			'layout' => 'excerpt',
			'mode'   => 'blog',
			'need_terms' => true,
			'title'  => esc_html__('Excerpt', 'jacqueline'),
			'thumb_title'  => esc_html__('Large image (crop)', 'jacqueline'),
			'w'		 => 770,
			'h'		 => 434
		));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_excerpt_output' ) ) {
	function jacqueline_template_excerpt_output($post_options, $post_data) {
		$show_title = (!in_array($post_data['post_format'], array('aside', 'status', 'link', 'quote')) ? true : false);
		$tag = jacqueline_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<<?php jacqueline_show_layout($tag); ?> <?php post_class('post_item post_item_excerpt post_featured_' . esc_attr($post_options['post_class']) . ' post_format_'.esc_attr($post_data['post_format']) . ($post_options['number']%2==0 ? ' even' : ' odd') . ($post_options['number']==0 ? ' first' : '') . ($post_options['number']==$post_options['posts_on_page']? ' last' : '') . ($post_options['add_view_more'] ? ' viewmore' : '')); ?>>
			<?php
			if ($post_data['post_flags']['sticky']) {
				?><span class="sticky_label"></span><?php
			}

			if ($show_title && $post_options['location'] == 'center' && !empty($post_data['post_title'])) {
				?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php jacqueline_show_layout($post_data['post_title']); ?></a></h3><?php
			}
			
			if (!$post_data['post_protected']) {
				?>
				<div class="post_featured">
				<?php
				if (!empty($post_options['dedicated'])) {
                    jacqueline_show_layout($post_options['dedicated']);
				} else if ($post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio']) {
					jacqueline_template_set_args('post-featured', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					get_template_part(jacqueline_get_file_slug('templates/_parts/post-featured.php'));
				}
				else if (in_array($post_data['post_format'], array('quote', 'link', 'aside', 'status')))
				{
					if($post_data['post_format'] == 'link')
                        jacqueline_show_layout('<h4 class="post_title">'.esc_html($post_data['post_title']).'</h4>');
                    jacqueline_show_layout($post_data['post_excerpt']);
				}
				?>
				</div>
			<?php
			}
			?>
	
			<div class="post_content clearfix">
				<?php
				if ($show_title && $post_options['location'] != 'center' && !empty($post_data['post_title'])) {
					?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php jacqueline_show_layout($post_data['post_title']); ?></a></h3><?php
				}
				
				if (!$post_data['post_protected'] && $post_options['info']) {
					$post_options['counters'] = 'views,captions';
					$post_options['info_parts'] = array('counters'=>true, 'terms'=>true, 'author'=>true, 'date'=>false);
					jacqueline_template_set_args('post-info', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					get_template_part(jacqueline_get_file_slug('templates/_parts/post-info.php')); 
				}
				?>
		
				<div class="post_descr">
				<?php
					if ($post_data['post_protected']) {
                        jacqueline_show_layout($post_data['post_excerpt']);
					} else {
						if ($post_data['post_excerpt']) {
							echo in_array($post_data['post_format'], array('quote', 'link', 'aside', 'status')) ? '' 
								: ($post_data['post_format'] == 'chat' ? $post_data['post_excerpt'] 
								: ('<p>'.trim(jacqueline_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] 
								: (jacqueline_get_custom_option('post_excerpt_maxlength'))).'</p>')));
						}
					}
					if (empty($post_options['readmore'])) $post_options['readmore'] = esc_html__('Continue Reading', 'jacqueline');
					if (!jacqueline_param_is_off($post_options['readmore']) 
						&& !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status', 'audio'))
						&& function_exists('jacqueline_sc_button')
						) {
                        jacqueline_show_layout(jacqueline_sc_button(array('link'=>$post_data['post_link']), $post_options['readmore']));
					}
				?>
				</div>

			</div>	<!-- /.post_content -->

		</<?php jacqueline_show_layout($tag); ?>>	<!-- /.post_item -->

	<?php
	}
}
?>