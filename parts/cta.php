<?php
/**
 * Call-to-action component
 *
 * @package Lumiere_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="cta minimal-section js-animate" data-animate="zoom-in">
	<div class="container narrow">
		<div class="cta-inner">
			<h2 class="cta-title"><?php esc_html_e( 'Prêt à réserver votre séance ?', 'lumiere-portfolio' ); ?></h2>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-minimal">
				<?php esc_html_e( 'Réserver une séance', 'lumiere-portfolio' ); ?>
			</a>
		</div>
	</div>
</section>
