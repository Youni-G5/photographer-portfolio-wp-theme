<?php
/**
 * Page template
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main">
	<section class="content-area page-layout minimal-layout">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</header>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</section>
</main>

<?php get_footer();
