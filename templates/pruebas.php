<?php
/**
* Template Name: Pruebas
*
* @package WordPress
* @subpackage jacqueline
* @since 1.0
*/

// Disable direct call
if (!defined( 'ABSPATH' )) { exit; }

get_header();
?>
<script src="https://gateway-na.americanexpress.com/checkout/version/56/checkout.js"
    data-error="errorCallback"
    data-cancel="cancelCallback"
>
</script>

<main>
    <?php while ( have_posts() ) : ?>
        <?php the_post() ?>

        <h2><?= the_title() ?></h2>
        <p><?= the_content() ?></p>

        <?php
        echo $woocommerce->cart->get_cart_total();
        echo floatval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_total()));
        ?>

        <input type="button" value="Pay with Lightbox" onclick="Checkout.showLightbox();" />
        <input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" />
    <?php endwhile; ?>
</main>

<!-- <script
    src="https://gateway-na.americanexpress.com/checkout/version/56/checkout.js"
    data-error="errorCallback"
    data-cancel="cancelCallback"
>
</script> -->
<?php
get_footer();