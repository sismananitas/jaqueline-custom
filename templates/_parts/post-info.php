<?php
// Get template args
extract(jacqueline_template_get_args('post-info'));

$info_parts = array_merge(
	[
		'snippets' 	=> false,	// For singular post/page/team/client/service etc.
		'date' 		=> true,
		'author' 	=> true,
		'terms' 	=> true,
		'counters' 	=> true,
		'tag' 		=> 'div'	// 'p' for portfolio hovers
	],
	isset($post_options['info_parts']) && is_array($post_options['info_parts']) ? $post_options['info_parts'] : array()
);

?>
<<?= esc_attr($info_parts['tag']); ?> class="post_info">
	<?php if ($info_parts['date'] && !in_array($post_data['post_type'], array('players', 'team'))) : ?>
		<?php
			$post_date = apply_filters('jacqueline_filter_post_date', $post_data['post_date_sql'], $post_data['post_id'], $post_data['post_type']);
			$post_date_diff = jacqueline_get_date_or_difference($post_date);
		?>
		<span class="post_info_item post_info_posted">
			<?php
				echo (in_array($post_data['post_type'], ['post', 'page', 'product'])
					? ''
					: ($post_date <= date('Y-m-d')
						? esc_html__('Started', 'jacqueline')
						: esc_html__('Will start', 'jacqueline')));
			?>
			<a href="<?= esc_url($post_data['post_link']); ?>" class="post_info_date<?= esc_attr($info_parts['snippets'] ? ' date updated' : ''); ?>"<?= !empty($info_parts['snippets']) ? ' itemprop="datePublished" content="'. esc_attr($post_date) .'"' : ''; ?>>
				<?= esc_html($post_date_diff); ?>
			</a>
		</span>
	<?php endif ?>
	<?php if ($info_parts['author'] && $post_data['post_type'] == 'post') : ?>
		<span class="post_info_item post_info_posted_by<?= !empty($info_parts['snippets']) ? ' vcard' : ''; ?>"<?= !empty($info_parts['snippets']) ? ' itemprop="author"' : ''; ?>>
			<?php esc_html_e('Posted by', 'jacqueline'); ?> <a href="<?= esc_url($post_data['post_author_url']); ?>" class="post_info_author"><?php jacqueline_show_layout($post_data['post_author']); ?></a>
		</span>
	<?php endif ?>

	<?php if ($info_parts['terms'] && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links)) : ?>
		<span class="post_info_item post_info_tags"><?php esc_html_e('in', 'jacqueline'); ?> <?= join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links); ?></span>
	<?php endif ?>

	<?php if ($info_parts['counters']): ?>
		<span class="post_info_item post_info_counters">
			<?php
				jacqueline_template_set_args('counters', [
					'post_options' => $post_options,
					'post_data'	   => $post_data
				]);
				get_template_part(jacqueline_get_file_slug('templates/_parts/counters.php'));
			?>
		</span>
	<?php endif ?>

	<?php if (is_single() && !jacqueline_storage_get('blog_streampage') && ($post_data['post_edit_enable'] || $post_data['post_delete_enable'])) : ?>
		<span class="frontend_editor_buttons">
			<?php if ($post_data['post_edit_enable']) : ?>
				<span class="post_info_item post_info_button post_info_button_edit">
					<a id="frontend_editor_icon_edit" class="icon-pencil" title="<?php esc_attr_e('Edit post', 'jacqueline'); ?>" href="#"><?php esc_html_e('Edit', 'jacqueline'); ?></a>
				</span>
			<?php endif ?>

			<?php if ($post_data['post_delete_enable']) : ?>
				<span class="post_info_item post_info_button post_info_button_delete">
					<a id="frontend_editor_icon_delete" class="icon-trash" title="<?php esc_attr_e('Delete post', 'jacqueline'); ?>" href="#"><?php esc_html_e('Delete', 'jacqueline'); ?></a>
				</span>
			<?php endif ?>
		</span>
	<?php endif ?>
</<?= esc_attr($info_parts['tag']); ?>>