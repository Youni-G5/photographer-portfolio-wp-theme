<?php
/**
 * Single post template
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main">
	<section class="content-area minimal-layout">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post minimal-single' ); ?>>
				<header class="single-header">
					<h1 class="single-title"><?php the_title(); ?></h1>
					<div class="single-meta"><?php echo get_the_date(); ?></div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-featured-image">
						<?php the_post_thumbnail( 'portfolio-large' ); ?>
					</div>
				<?php endif; ?>

				<div class="single-content">
					<?php the_content(); ?>
				</div>
			</article>

			<nav class="post-navigation minimal-nav">
				<div class="nav-previous"><?php previous_post_link( '%link', __( 'Article précédent', 'lumiere-portfolio' ) ); ?></div>
				<div class="nav-next"><?php next_post_link( '%link', __( 'Article suivant', 'lumiere-portfolio' ) ); ?></div>
			</nav>
		<?php endwhile; ?>
	</section>
</main>

<?php get_footer();
