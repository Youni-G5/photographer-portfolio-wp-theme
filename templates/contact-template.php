<?php
/**
 * Template Name: Contact
 * Description: Modèle de page pour le contact avec formulaire simple et coordonnées.
 *
 * @package Lumiere_Portfolio
 */

get_header(); ?>

<main id="primary" class="site-main contact-template">
	<section class="content-area minimal-layout">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'contact-layout' ); ?>>
				<header class="page-header">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</header>

				<div class="contact-layout__grid">
					<div class="contact-layout__form">
						<form class="minimal-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
							<input type="hidden" name="action" value="lumiere_contact_form">
							<?php wp_nonce_field( 'lumiere_contact_form', 'lumiere_contact_nonce' ); ?>

							<div class="form-group">
								<label for="lumiere-name"><?php esc_html_e( 'Nom', 'lumiere-portfolio' ); ?></label>
								<input type="text" id="lumiere-name" name="name" required>
							</div>

							<div class="form-group">
								<label for="lumiere-email"><?php esc_html_e( 'Email', 'lumiere-portfolio' ); ?></label>
								<input type="email" id="lumiere-email" name="email" required>
							</div>

							<div class="form-group">
								<label for="lumiere-message"><?php esc_html_e( 'Message', 'lumiere-portfolio' ); ?></label>
								<textarea id="lumiere-message" name="message" rows="5" required></textarea>
							</div>

							<button type="submit" class="btn-minimal"><?php esc_html_e( 'Envoyer', 'lumiere-portfolio' ); ?></button>
						</form>
					</div>

					<div class="contact-layout__info">
						<h2 class="section-title"><?php esc_html_e( 'Coordonnées', 'lumiere-portfolio' ); ?></h2>
						<ul class="contact-details">
							<li><span>Email</span> <a href="mailto:contact@votre-studio.com">contact@votre-studio.com</a></li>
							<li><span>Instagram</span> <a href="https://instagram.com/votreprofil" target="_blank" rel="noopener">@votreprofil</a></li>
							<li><span>Téléphone</span> <a href="tel:+33000000000">+33 0 00 00 00 00</a></li>
						</ul>

						<div class="contact-map">
							<!-- Intégrez ici un iframe de Google Maps ou une image statique -->
							<div class="map-placeholder">
								<p><?php esc_html_e( 'Carte de votre studio ou zone d’intervention.', 'lumiere-portfolio' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</section>
</main>

<?php get_footer();
