<?php
/**
 * Template Name: Galerie - Masonry
 * Description: Modèle de page pour afficher une grille masonry de galeries avec filtre par catégories.
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main gallery-template">
	<section class="content-area minimal-layout">
		<header class="page-header">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</header>

		<div class="gallery-filters">
			<button class="filter-btn is-active" data-filter="*"><?php esc_html_e( 'Toutes', 'lumiere-portfolio' ); ?></button>
			<?php
				$terms = get_terms( array(
					'taxonomy'   => 'lumiere_genre',
					'hide_empty' => true,
				) );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
					foreach ( $terms as $term ) :
						printf(
							'<button class="filter-btn" data-filter=".genre-%1$s">%2$s</button>',
							esc_attr( $term->slug ),
							esc_html( $term->name )
						);
					endforeach;
				endif;
			?>
		</div>

		<div class="gallery-grid masonry-grid js-masonry-grid">
			<?php
				$query = new WP_Query( array(
					'post_type'      => 'lumiere_gallery',
					'posts_per_page' => -1,
				) );

				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) : $query->the_post();
						$genres = get_the_terms( get_the_ID(), 'lumiere_genre' );
						$genre_classes = '';
						if ( $genres && ! is_wp_error( $genres ) ) {
							foreach ( $genres as $genre ) {
								$genre_classes .= ' genre-' . $genre->slug;
							}
						}
				?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery-item js-lightbox-trigger' . esc_attr( $genre_classes ) ); ?> data-lightbox-group="galleries">
						<a href="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'portfolio-large' ) ); ?>" class="gallery-item__link" data-title="<?php the_title_attribute(); ?>">
							<?php if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'portfolio-grid' );
							} ?>
							<div class="gallery-item__overlay">
								<h3 class="gallery-item__title"><?php the_title(); ?></h3>
							</div>
						</a>
					</article>
				<?php
					endwhile;
					wp_reset_postdata();
				else :
				?>
					<p class="minimal-empty"><?php esc_html_e( 'Aucune galerie disponible pour le moment.', 'lumiere-portfolio' ); ?></p>
				<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer();
