<?php
/**
 * Gallery grid component (used on front page)
 *
 * @package Lumiere_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$galleries = new WP_Query( array(
	'post_type'      => 'lumiere_gallery',
	'posts_per_page' => 8,
) );
?>

<div class="gallery-grid masonry-grid js-masonry-grid">
	<?php if ( $galleries->have_posts() ) : ?>
		<?php while ( $galleries->have_posts() ) : $galleries->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery-item js-lightbox-trigger' ); ?> data-lightbox-group="home-galleries">
				<a href="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'portfolio-large' ) ); ?>" class="gallery-item__link" data-title="<?php the_title_attribute(); ?>">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'portfolio-grid' );
					} ?>
					<div class="gallery-item__overlay">
						<h3 class="gallery-item__title"><?php the_title(); ?></h3>
					</div>
				</a>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	<?php else : ?>
		<p class="minimal-empty"><?php esc_html_e( 'Aucune galerie pour le moment.', 'lumiere-portfolio' ); ?></p>
	<?php endif; ?>
</div>
