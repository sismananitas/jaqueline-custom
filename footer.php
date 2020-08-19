<?php
/**
 * The template for displaying the footer.
 */

				jacqueline_close_wrapper();	// <!-- </.content> -->

				jacqueline_profiler_add_point(esc_html__('After Page content', 'jacqueline'));
	
				// Show main sidebar
				get_sidebar();

				if (jacqueline_get_custom_option('body_style')!='fullscreen') jacqueline_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			
			// Footer sidebar
			get_template_part(jacqueline_get_file_slug('templates/_parts/footer-sidebar.php'));

			// Footer contacts
			get_template_part(jacqueline_get_file_slug('templates/_parts/footer-contacts.php'));
			
			// Copyright area
			get_template_part(jacqueline_get_file_slug('templates/_parts/footer-copyright-area.php'));
			
			jacqueline_profiler_add_point(esc_html__('After Footer', 'jacqueline'));
			?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !jacqueline_param_is_off(jacqueline_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
// Post/Page views counter
get_template_part(jacqueline_get_file_slug('templates/_parts/views-counter.php'));

// Front customizer
if (jacqueline_get_custom_option('show_theme_customizer')=='yes') {
	require_once trailingslashit( get_template_directory() ) . 'core/core.customizer/front.customizer.php';
}


// Scroll to top
if (jacqueline_get_custom_option('scroll_to_top')=='yes') {
	?>
	<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'jacqueline'); ?>"></a>
	<?php
}
?>


<div class="custom_html_section">
<?php jacqueline_show_layout(jacqueline_get_custom_option('custom_code')); ?>
</div>

<?php
jacqueline_show_layout(jacqueline_get_custom_option('gtm_code2'));

jacqueline_profiler_add_point(esc_html__('After Theme HTML output', 'jacqueline'));
	
wp_footer(); 
?>

<a class="whatsapp-movil" rel="nofollow" href="whatsapp://send/?phone=527773280080">
	<i class="fab fa-whatsapp fa-2x"></i>
</a>
<a class="whatsapp-web" rel="nofollow" href="https://web.whatsapp.com/send/?phone=527773280080" target="_blank">
	<i class="fab fa-whatsapp fa-2x"></i>
</a>

<script src="https://gateway-na.americanexpress.com/checkout/version/56/checkout.js"
	data-error="errorCallback"
	data-cancel="cancelCallback">
</script>
<script>
	function errorCallback(error) {
		console.log(JSON.stringify(error));
	}
	function cancelCallback() {
		console.log('Payment cancelled');
	}

	Checkout.configure({
		merchant: '82622017056',
		order: {
			amount: function() {
				//Dynamic calculation of amount
				return 80 + 20;
			},
			currency: 'MXN',
			description: 'Ordered goods',
			id: ''
		},
		interaction: {
			operation: 'PURCHASE', // set this field to 'PURCHASE' for Hosted Checkout to perform a Pay Operation.
			merchant: {
				name: 'hotel mañanitas',
				address: {
					line1: '200 Sample St',
					line2: '1234 Example Town'            
				}    
			}
		}
	});
</script>

</body>
</html>