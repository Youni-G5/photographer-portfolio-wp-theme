<?php
/**
 * Template Name: À propos du photographe
 * Description: Modèle de page minimaliste pour présenter le photographe.
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main about-template">
	<section class="content-area minimal-layout">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'about-layout' ); ?>>
				<div class="about-layout__grid">
					<div class="about-layout__photo">
						<?php if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'portfolio-large' );
						} ?>
					</div>
					<div class="about-layout__content">
						<h1 class="page-title"><?php the_title(); ?></h1>
						<div class="page-content">
							<?php the_content(); ?>
						</div>

						<section class="about-awards">
							<h2 class="section-title"><?php esc_html_e( 'Awards & Expositions', 'lumiere-portfolio' ); ?></h2>
							<ul class="awards-list">
								<li><?php esc_html_e( 'Prix ou exposition 1', 'lumiere-portfolio' ); ?></li>
								<li><?php esc_html_e( 'Prix ou exposition 2', 'lumiere-portfolio' ); ?></li>
								<li><?php esc_html_e( 'Prix ou exposition 3', 'lumiere-portfolio' ); ?></li>
							</ul>
						</section>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</section>
</main>

<?php get_footer();
