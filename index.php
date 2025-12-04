<?php
/**
 * Main template file
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main">
	<section class="content-area minimal-layout">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			</header>

			<div class="post-list minimal-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'minimal-post' ); ?>>
						<a href="<?php the_permalink(); ?>" class="minimal-post__thumb">
							<?php if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'portfolio-grid' );
							} ?>
						</a>
						<div class="minimal-post__content">
							<h2 class="minimal-post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="minimal-post__meta"><?php echo get_the_date(); ?></div>
							<div class="minimal-post__excerpt"><?php the_excerpt(); ?></div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<section class="no-results minimal-empty">
				<h2><?php esc_html_e( 'Aucun contenu disponible pour le moment.', 'lumiere-portfolio' ); ?></h2>
			</section>
		<?php endif; ?>
	</section>
</main>

<?php get_footer();
