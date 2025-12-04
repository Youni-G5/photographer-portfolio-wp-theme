<?php
/**
 * Testimonials component (static minimal block by default)
 *
 * @package Lumiere_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="testimonials minimal-section light-bg js-animate" data-animate="slide-up">
	<div class="container narrow">
		<h2 class="section-title"><?php esc_html_e( 'Ils ont fait confiance au studio', 'lumiere-portfolio' ); ?></h2>
		<div class="testimonials-grid">
			<article class="testimonial">
				<p class="testimonial-text"><?php esc_html_e( '“Une expérience incroyable. Les images respirent la douceur et la précision.”', 'lumiere-portfolio' ); ?></p>
				<p class="testimonial-author">— <?php esc_html_e( 'Camille & Julien', 'lumiere-portfolio' ); ?></p>
			</article>
			<article class="testimonial">
				<p class="testimonial-text"><?php esc_html_e( '“Une présence discrète, un regard unique. Chaque photo raconte une histoire.”', 'lumiere-portfolio' ); ?></p>
				<p class="testimonial-author">— <?php esc_html_e( 'Sarah', 'lumiere-portfolio' ); ?></p>
			</article>
			<article class="testimonial">
				<p class="testimonial-text"><?php esc_html_e( '“Un rendu sublime, parfaitement aligné avec l’esthétique de notre marque.”', 'lumiere-portfolio' ); ?></p>
				<p class="testimonial-author">— <?php esc_html_e( 'Studio créatif Lumen', 'lumiere-portfolio' ); ?></p>
			</article>
		</div>
	</div>
</section>
