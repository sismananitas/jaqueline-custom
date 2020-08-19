<?php
/*
Template Name: Blog streampage
*/

/**
 * Make empty page with this template
 * and put it into menu
 * to display all posts as streampage
 */

jacqueline_storage_set('blog_streampage', true);

get_header();

global $wp_query, $post;

?>
<?php $title = $post->post_title ?>

<!-- POST CONTENT -->
<div style="margin-bottom: 2rem">
<?= $post->post_content ?>
</div>

<?php if (strtolower($title) == 'servicios') { ?>
	<h3>Todos nuestros servicios incluyen:</h3>
	<div class="flex justify-content-between">
		<ul class="col">
			<li><p>Uso de batas y sandalias</p></li>
			<li><p>Elegante área de casilleros (por día)</p></li>
			<li><p>Amenidades spa</p></li>
			<li><p>Toallas frías con aroma terapia</p></li>
			<li><p>Atención personalizada</p></li>
			<li><p>Variedades de te</p></li>
		</ul>
		<ul class="col">
			<li><p>Agua de clorofila y Jamaica</p></li>
			<li><p>Fruta de la estación</p></li>
			<li><p>Frutos secos</p></li>
			<li><p>Bebidas aromáticas refrescantes</p></li>
			<li><p>Día Spa de 8am a 8pm</p></li>
		</ul>
		<ul class="col">
			<li><p>Terraza lounge con servicio de restaurante</p></li>
			<li><p>Ritual hidroterapia aqua (sauna, vapor, jacuzzi, regadera de sensaciones y laguna de inmersión)</p></li>
		</ul>
	</div>
<?php } ?>

<hr><br>

<?php
if (empty($blog_style))	: $blog_style = jacqueline_get_custom_option('blog_style'); endif;
$blog_columns	= max(1, (int) jacqueline_substr($blog_style, -1));
$show_sidebar 	= jacqueline_get_custom_option('show_sidebar_main');
$show_filters 	= jacqueline_get_custom_option('show_filters');
$ppp			= (int) jacqueline_get_custom_option('posts_per_page');
$hover			= 'inherit';
$hover_dir		= 'inherit';

$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);

$args = $wp_query->query_vars;
$args['post_status'] = current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish';

if ( is_page() || jacqueline_storage_get('blog_filters') != '' ) {
	unset($args['p']);
	unset($args['page_id']);
	unset($args['pagename']);
	unset($args['name']);
	$args['posts_per_page'] = $ppp;
	if ($page_number > 1) {
		$args['paged'] = $page_number;
		$args['ignore_sticky_posts'] = true;
	}
	$args = jacqueline_query_add_sort_order($args);
	$args = jacqueline_query_add_filters($args, jacqueline_storage_get('blog_filters'));

	query_posts( $args );
}

$per_page 	   = count($wp_query->posts);
$post_number   = 0;
$parent_tax_id = (int) jacqueline_get_custom_option('taxonomy_id');
$flt_ids 	   = array();

$container = apply_filters('jacqueline_filter_blog_container', jacqueline_get_template_property($blog_style, 'container'), array('style'=>$blog_style, 'dir'=>'horizontal'));
$container_start = $container_end = '';
if (!empty($container)) {
	$container 		 = explode('%s', $container);
	$container_start = !empty($container[0]) ? $container[0] : '';
	$container_end 	 = !empty($container[1]) ? $container[1] : '';
}

$container2 = apply_filters('jacqueline_filter_blog_container2', jacqueline_get_template_property($blog_style, 'container2'), array('style'=>$blog_style, 'dir'=>'horizontal'));
$container2_start = $container2_end = '';
if (!empty($container2)) {
	$container2 	  = explode('%s', $container2);
	$container2_start = !empty($container2[0]) ? $container2[0] : '';
	$container2_end	  = !empty($container2[1]) ? $container2[1] : '';
}

jacqueline_show_layout('', $container_start, $container2_start);

// Wrappers
if (jacqueline_get_template_property($blog_style, 'need_columns') && $blog_columns > 1) {
	?>
	<div class="columns_wrap <?php echo esc_attr(jacqueline_get_template_property($blog_style, 'container_classes')); ?>">
<?php } ?>

<?php if (jacqueline_get_template_property($blog_style, 'need_isotope')) : ?>
	<?php if (!jacqueline_param_is_off($show_filters)) : ?>
		<div class="isotope_filters"></div>
	<?php endif ?>
	<div class="isotope_wrap <?php echo esc_attr(jacqueline_get_template_property($blog_style, 'container_classes')); ?>" data-columns="<?php echo esc_attr($blog_columns); ?>">
<?php endif ?>

<?php
while ( have_posts() ) :
	the_post();
	$post_number++;
	$post_args = [
		'layout' 		=> $blog_style,
		'number' 		=> $post_number,
		'add_view_more' => false,
		'posts_on_page' => $per_page,
		'columns_count' => $blog_columns,
		// Get post data
		'strip_teaser' 	=> false,
		'content' 		=> jacqueline_get_template_property($blog_style, 'need_content'),
		'terms_list' 	=> !jacqueline_param_is_off($show_filters) || jacqueline_get_template_property($blog_style, 'need_terms'),
		'parent_tax_id' => $parent_tax_id,
		'descr' 		=> jacqueline_get_custom_option('post_excerpt_maxlength'.($blog_columns > 1 ? '_masonry' : '')),
		'sidebar' 		=> !jacqueline_param_is_off($show_sidebar),
		'filters' 		=> $show_filters != 'hide' ? $show_filters : '',
		'hover' 		=> $hover,
		'hover_dir' 	=> $hover_dir
	];

	$post_data = jacqueline_get_post_data($post_args);

	jacqueline_show_post_layout($post_args, $post_data);

	if ($show_filters == 'tags') {	// Use tags as filter items
		if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
			foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
				$flt_ids[$tag->term_id] = $tag->name;
			}
		}
	}
endwhile;

if (jacqueline_get_template_property($blog_style, 'need_isotope')) { ?>
	</div> <!-- /.isotope_wrap -->
<?php }

if (jacqueline_get_template_property($blog_style, 'need_columns') && $blog_columns > 1) { ?>
	</div> <!-- /.columns_wrap -->
<?php }

jacqueline_show_layout('', $container2_end, $container_end);

if (!$post_number) {
	if ( is_search() ) {
		jacqueline_show_post_layout( array('layout' => 'no-search'), false );
	} else {
		jacqueline_show_post_layout( array('layout' => 'no-articles'), false );
	}
} else {
	// Isotope filters list
	$filters = '';
	$filter_button_classes = 'isotope_filters_button';
	if ($show_filters == 'categories') {	// Use categories as filter items
		$taxonomy = jacqueline_is_taxonomy();
		$cur_term = $taxonomy ? jacqueline_get_current_term($taxonomy) : 0;
		$cur_term_id = $cur_term ? $cur_term->term_id : 0;
		$portfolio_parent = $cur_term_id ? max(0, jacqueline_get_parent_taxonomy_by_property($cur_term_id, 'show_filters', 'yes', true, $taxonomy)) : 0;
		$args2 = array(
			'type'			=> !empty($args['post_type']) ? $args['post_type'] : 'post',
			'child_of'		=> $portfolio_parent,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $taxonomy,
			'pad_counts'	=> false
		);
		$portfolio_list = get_categories($args2);
		if (is_array($portfolio_list) && count($portfolio_list) > 0) {
			$filters .= '<a href="#" data-filter="*" class="'.esc_attr($filter_button_classes . ($portfolio_parent == $cur_term_id ? ' active' : '')) . '">' . esc_html__('All', 'jacqueline').'</a>';
			foreach ($portfolio_list as $cat) {
				if (isset($cat->term_id)) $filters .= '<a href="#" data-filter=".flt_'.esc_attr($cat->term_id).'" class="'.esc_attr($filter_button_classes . ($cat->term_id == $cur_term_id ? ' active' : '')).'">'.($cat->name).'</a>';
			}
		}
	} else if ($show_filters == 'tags') {					// Use tags as filter items
		if (is_array($flt_ids) && count($flt_ids) > 0) {
			$filters .= '<a href="#" data-filter="*" class="'.esc_attr($filter_button_classes).' active">'.esc_html__('All', 'jacqueline').'</a>';
			foreach ($flt_ids as $flt_id=>$flt_name) {
				$filters .= '<a href="#" data-filter=".flt_'.esc_attr($flt_id).'" class="'.esc_attr($filter_button_classes).'">'.($flt_name).'</a>';
			}
		}
	}
	if ($filters) {
		jacqueline_storage_set_array('js_vars', 'ppp', intval($ppp));
		jacqueline_storage_concat('js_code', sprintf('jQuery(".isotope_filters").append(\'%s\');', $filters));
	}
}

if ($post_number > 0) :
	// Pagination
	$pagination = jacqueline_get_custom_option('blog_pagination'); ?>
	<?php if (in_array($pagination, array('viewmore', 'infinite'))) : ?>
		<?php if ($page_number < $wp_query->max_num_pages) : ?>
			<div id="viewmore" class="pagination_wrap pagination_<?php echo esc_attr($pagination); ?>">
				<a href="#" id="viewmore_link" class="theme_button viewmore_button">
					<span class="icon-spin3 animate-spin viewmore_loading"></span><span class="viewmore_text_1"><?php esc_html_e('LOAD MORE', 'jacqueline'); ?></span><span class="viewmore_text_2"><?php esc_html_e('Loading ...', 'jacqueline'); ?></span>
				</a>
				<span class="viewmore_loader"></span>
				<?php
					jacqueline_storage_set_array('js_vars', 'viewmore_page', intval($page_number));
					jacqueline_storage_set_array('js_vars', 'viewmore_data', serialize($args));
					jacqueline_storage_set_array('js_vars', 'viewmore_vars', serialize([
						'blog_style' 	=> $blog_style,
						'columns_count' => $blog_columns,
						'parent_tax_id' => $parent_tax_id,
						'show_sidebar' 	=> $show_sidebar,
						'filters' 		=> $show_filters != 'hide' ? $show_filters : '',
						'hover' 		=> $hover,
						'hover_dir' 	=> $hover_dir,
						'ppp' 			=> $ppp,
					]));
				?>
			</div>
		<?php endif;
	else:
		jacqueline_show_pagination([
			'class' 		 => 'pagination_wrap pagination_'.esc_attr($pagination),
			'style' 		 => $pagination,
			'button_class' 	 => '',
			'first_text'	 => '',
			'last_text' 	 => '',
			'prev_text' 	 => '',
			'next_text' 	 => '',
			'pages_in_group' => $pagination == 'pages' ? 10 : 20
		]);
	endif;
endif;

// Add template specific scripts and styles
do_action('jacqueline_action_blog_scripts', $blog_style);

// Restore main WP query
if (false) {
	if ( $wp_query_need_restore ) wp_reset_query();
	wp_reset_postdata();
}

get_footer();
?>