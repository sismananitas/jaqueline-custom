<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jacqueline_template_form_3_theme_setup' ) ) {
	add_action( 'jacqueline_action_before_init_theme', 'jacqueline_template_form_3_theme_setup', 1 );
	function jacqueline_template_form_3_theme_setup() {
		if (function_exists('jacqueline_sc_form_show_fields'))
			jacqueline_add_template(array(
				'layout' => 'form_3',
				'mode'   => 'forms',
				'title'  => esc_html__('Contact Form 3', 'jacqueline')
				));
	}
}

// Template output
if ( !function_exists( 'jacqueline_template_form_3_output' ) ) {
	function jacqueline_template_form_3_output($post_options, $post_data) {
        static $cnt = 0;
        $cnt++;
        $privacy = jacqueline_get_privacy_text();
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
			<?php jacqueline_sc_form_show_fields($post_options['fields']); ?>
			<div class="sc_form_info">
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'jacqueline'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Your Name', 'jacqueline'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'jacqueline'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail Address', 'jacqueline'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'jacqueline'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_attr_e('Subject', 'jacqueline'); ?>"></div>
			</div>
			<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'jacqueline'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Your Message', 'jacqueline'); ?>"></textarea></div>
            <?php
            if (!empty($privacy)) {
                ?><div class="sc_form_field sc_form_field_checkbox"><?php
                ?><input type="checkbox" id="i_agree_privacy_policy_sc_form_<?php echo esc_attr($cnt); ?>" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
                <label for="i_agree_privacy_policy_sc_form_<?php echo esc_attr($cnt); ?>"><?php jacqueline_show_layout($privacy); ?></label>
                </div><?php
            }
            ?>
            <div class="sc_form_item sc_form_button"><button class="sc_button sc_button_style_filled sc_button_size_medium" <?php
                if (!empty($privacy)) echo ' disabled="disabled"'
                ?>>
				<span class="overlay">
					<span class="first"><?php esc_html_e('Submit message', 'jacqueline'); ?></span>
					<span class="second"><?php esc_html_e('Submit message', 'jacqueline'); ?></span>				
				</span>
			</button></div>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>