<?php
/**
 * Header template
 *
 * @package Lumiere_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<?php wp_body_open(); ?>

<header class="site-header minimal-header">
	<div class="header-inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<div class="site-logo"><?php the_custom_logo(); ?></div>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title">\
					<?php bloginfo( 'name' ); ?>
				</a>
				<span class="site-tagline"><?php bloginfo( 'description' ); ?></span>
			<?php endif; ?>
		</div>

		<button class="nav-toggle" aria-expanded="false" aria-controls="primary-menu">
			<span class="nav-toggle__line"></span>
			<span class="nav-toggle__line"></span>
		</button>

		<nav class="primary-navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'lumiere-portfolio' ); ?>">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'header_menu',
					'menu_id'        => 'primary-menu',
					'container'      => false,
				) );
			?>
		</nav>
	</div>
</header>
