<?php 
if (is_singular() && jacqueline_get_theme_option('use_ajax_views_counter')=='no') {
	jacqueline_set_post_views(get_the_ID());
}
?>