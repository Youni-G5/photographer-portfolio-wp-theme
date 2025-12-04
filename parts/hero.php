<?php
/**
 * Hero component
 *
 * @package Lumiere_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_title   = get_theme_mod( 'lumiere_hero_title', __( 'Lumière & Émotion', 'lumiere-portfolio' ) );
$hero_subtitle = get_theme_mod( 'lumiere_hero_subtitle', __( 'Portfolio de photographie minimaliste, dédié à la lumière, aux lignes et aux instants sincères.', 'lumiere-portfolio' ) );
$hero_button_text = get_theme_mod( 'lumiere_hero_button_text', __( 'Découvrir les galeries', 'lumiere-portfolio' ) );
$hero_button_url  = get_theme_mod( 'lumiere_hero_button_url', get_post_type_archive_link( 'lumiere_gallery' ) );
?>

<section class="hero hero-minimal js-animate" data-animate="fade-in">
	<div class="container narrow">
		<div class="hero-content">
			<h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
			<p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
			<?php if ( $hero_button_text && $hero_button_url ) : ?>
				<a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn-minimal">
					<?php echo esc_html( $hero_button_text ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
