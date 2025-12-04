<?php
/**
 * Single gallery template for lumiere_gallery CPT
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main single-gallery-layout">
	<section class="content-area minimal-layout">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-gallery' ); ?>>
				<header class="single-header">
					<h1 class="single-title"><?php the_title(); ?></h1>
					<div class="single-meta">
						<?php
							$genres   = get_the_term_list( get_the_ID(), 'lumiere_genre', '', ' / ' );
							$sessions = get_the_term_list( get_the_ID(), 'lumiere_session', '', ' / ' );
							if ( $genres ) {
								echo wp_kses_post( $genres );
							}
							if ( $sessions ) {
								echo ' · ' . wp_kses_post( $sessions );
							}
						?>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-featured-image">
						<?php the_post_thumbnail( 'portfolio-large' ); ?>
					</div>
				<?php endif; ?>

				<div class="single-gallery-grid gallery-grid">
					<?php
						// Option simple: on affiche le contenu comme liste d'images (via Gutenberg ou Elementor)
						// Tu pourras remplacer par un champ répéteur ACF si besoin.
						the_content();
					?>
				</div>
			</article>

			<nav class="post-navigation minimal-nav">
				<div class="nav-previous"><?php previous_post_link( '%link', __( 'Galerie précédente', 'lumiere-portfolio' ) ); ?></div>
				<div class="nav-next"><?php next_post_link( '%link', __( 'Galerie suivante', 'lumiere-portfolio' ) ); ?></div>
			</nav>
		<?php endwhile; ?>
	</section>
</main>

<?php get_footer();
