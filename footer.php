<?php
/**
 * Footer template
 *
 * @package Lumiere_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<footer class="site-footer minimal-footer">
	<div class="footer-inner">
		<div class="footer-left">
			<p>&copy; <?php echo date_i18n( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Tous droits réservés.', 'lumiere-portfolio' ); ?></p>
		</div>
		<div class="footer-right">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'footer_menu',
					'menu_id'        => 'footer-menu',
					'container'      => false,
				) );
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
