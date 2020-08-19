<?php
		$copyright_style = jacqueline_get_custom_option('show_copyright_in_footer');
		if (!jacqueline_param_is_off($copyright_style)) {
			?> 
			<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(jacqueline_get_custom_option('copyright_scheme')); ?>">
				<div class="copyright_wrap_inner">
					<div class="content_wrap">
						<?php
						if ($copyright_style == 'menu') {
							if (($menu = jacqueline_get_nav_menu('menu_footer'))!='') {
                                jacqueline_show_layout($menu);
							}
						} else if ($copyright_style == 'socials') { ?>
						    <div class="social_footer">
						    <?php
                            echo '<div class="beforeSocials">'.esc_html__('Connect With Us:', 'jacqueline').'</div>';
                            if (function_exists('jacqueline_sc_socials')) jacqueline_show_layout(jacqueline_sc_socials(array('size'=>"tiny", 'type'=>"text")));
						}
						?></div>
						<div class="copyright_text"><?php echo force_balance_tags(do_shortcode(str_replace(array('{{Y}}', '{Y}'), date('Y'), jacqueline_get_custom_option('footer_copyright')))); ?></div>
					</div>
				</div>
			</div>
			<?php
		}
?>