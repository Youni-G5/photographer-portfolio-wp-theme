<?php
/**
 * Front page template
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main home-layout">
	<?php get_template_part( 'parts/hero' ); ?>

	<section class="home-intro minimal-section">
		<div class="container narrow">
			<h2 class="section-title"><?php esc_html_e( 'Photographe basé à...', 'lumiere-portfolio' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Spécialisé dans les portraits lumineux, les mariages élégants et les scènes urbaines minimalistes.', 'lumiere-portfolio' ); ?></p>
		</div>
	</section>

	<section class="home-galleries minimal-section">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title"><?php esc_html_e( 'Nouvelles galeries', 'lumiere-portfolio' ); ?></h2>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'lumiere_gallery' ) ); ?>" class="link-minimal"><?php esc_html_e( 'Voir toutes les galeries', 'lumiere-portfolio' ); ?></a>
			</div>
			<?php get_template_part( 'parts/gallery-grid' ); ?>
		</div>
	</section>

	<?php get_template_part( 'parts/testimonials' ); ?>
	<?php get_template_part( 'parts/cta' ); ?>
</main>

<?php get_footer();
