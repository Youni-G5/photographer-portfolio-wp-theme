<?php
/**
 * Theme Customizer Settings
 *
 * @package Lumière_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings
 */
function lumiere_customize_register( $wp_customize ) {

	// ===== SECTION: COLORS & BRANDING =====
	$wp_customize->add_section( 'lumiere_colors', array(
		'title'    => __( 'Couleurs & Branding', 'lumiere-portfolio' ),
		'priority' => 30,
	) );

	// Primary Color
	$wp_customize->add_setting( 'lumiere_primary_color', array(
		'default'           => '#1a1a1a',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'lumiere_primary_color', array(
		'label'   => __( 'Couleur principale', 'lumiere-portfolio' ),
		'section' => 'lumiere_colors',
	) ) );

	// Accent Color
	$wp_customize->add_setting( 'lumiere_accent_color', array(
		'default'           => '#d4af37',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'lumiere_accent_color', array(
		'label'   => __( 'Couleur d\'accent', 'lumiere-portfolio' ),
		'section' => 'lumiere_colors',
	) ) );

	// Background Color
	$wp_customize->add_setting( 'lumiere_bg_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'lumiere_bg_color', array(
		'label'   => __( 'Couleur de fond', 'lumiere-portfolio' ),
		'section' => 'lumiere_colors',
	) ) );

	// Text Color
	$wp_customize->add_setting( 'lumiere_text_color', array(
		'default'           => '#333333',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'lumiere_text_color', array(
		'label'   => __( 'Couleur du texte', 'lumiere-portfolio' ),
		'section' => 'lumiere_colors',
	) ) );

	// ===== SECTION: TYPOGRAPHY =====
	$wp_customize->add_section( 'lumiere_typography', array(
		'title'    => __( 'Typographie', 'lumiere-portfolio' ),
		'priority' => 35,
	) );

	// Heading Font
	$wp_customize->add_setting( 'lumiere_heading_font', array(
		'default'           => 'Playfair Display',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'lumiere_heading_font', array(
		'label'   => __( 'Police des titres', 'lumiere-portfolio' ),
		'section' => 'lumiere_typography',
		'type'    => 'select',
		'choices' => array(
			'Playfair Display' => 'Playfair Display',
			'Cormorant Garamond' => 'Cormorant Garamond',
			'Libre Baskerville' => 'Libre Baskerville',
			'Montserrat' => 'Montserrat',
			'Raleway' => 'Raleway',
		),
	) );

	// Body Font
	$wp_customize->add_setting( 'lumiere_body_font', array(
		'default'           => 'Inter',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'lumiere_body_font', array(
		'label'   => __( 'Police du texte', 'lumiere-portfolio' ),
		'section' => 'lumiere_typography',
		'type'    => 'select',
		'choices' => array(
			'Inter' => 'Inter',
			'Open Sans' => 'Open Sans',
			'Lato' => 'Lato',
			'Roboto' => 'Roboto',
			'Source Sans Pro' => 'Source Sans Pro',
		),
	) );

	// Font Size
	$wp_customize->add_setting( 'lumiere_font_size', array(
		'default'           => '16',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'lumiere_font_size', array(
		'label'       => __( 'Taille de police (px)', 'lumiere-portfolio' ),
		'section'     => 'lumiere_typography',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 14,
			'max'  => 20,
			'step' => 1,
		),
	) );

	// ===== SECTION: LAYOUT =====
	$wp_customize->add_section( 'lumiere_layout', array(
		'title'    => __( 'Mise en page', 'lumiere-portfolio' ),
		'priority' => 40,
	) );

	// Site Layout
	$wp_customize->add_setting( 'lumiere_site_layout', array(
		'default'           => 'full-width',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'lumiere_site_layout', array(
		'label'   => __( 'Largeur du site', 'lumiere-portfolio' ),
		'section' => 'lumiere_layout',
		'type'    => 'select',
		'choices' => array(
			'full-width' => __( 'Pleine largeur', 'lumiere-portfolio' ),
			'boxed'      => __( 'Encadré', 'lumiere-portfolio' ),
		),
	) );

	// Container Width
	$wp_customize->add_setting( 'lumiere_container_width', array(
		'default'           => '1200',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'lumiere_container_width', array(
		'label'       => __( 'Largeur du conteneur (px)', 'lumiere-portfolio' ),
		'section'     => 'lumiere_layout',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 960,
			'max'  => 1920,
			'step' => 10,
		),
	) );

	// ===== SECTION: GALLERY SETTINGS =====
	$wp_customize->add_section( 'lumiere_gallery_settings', array(
		'title'    => __( 'Paramètres des galeries', 'lumiere-portfolio' ),
		'priority' => 45,
	) );

	// Default Gallery Layout
	$wp_customize->add_setting( 'lumiere_default_gallery_layout', array(
		'default'           => 'masonry',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'lumiere_default_gallery_layout', array(
		'label'   => __( 'Mise en page par défaut', 'lumiere-portfolio' ),
		'section' => 'lumiere_gallery_settings',
		'type'    => 'select',
		'choices' => array(
			'grid'      => __( 'Grille', 'lumiere-portfolio' ),
			'masonry'   => __( 'Masonry', 'lumiere-portfolio' ),
			'justified' => __( 'Justifié', 'lumiere-portfolio' ),
			'slider'    => __( 'Slider', 'lumiere-portfolio' ),
		),
	) );

	// Gallery Columns
	$wp_customize->add_setting( 'lumiere_gallery_columns', array(
		'default'           => '3',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'lumiere_gallery_columns', array(
		'label'   => __( 'Nombre de colonnes', 'lumiere-portfolio' ),
		'section' => 'lumiere_gallery_settings',
		'type'    => 'select',
		'choices' => array(
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
		),
	) );

	// Gallery Gap
	$wp_customize->add_setting( 'lumiere_gallery_gap', array(
		'default'           => '20',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'lumiere_gallery_gap', array(
		'label'       => __( 'Espacement entre images (px)', 'lumiere-portfolio' ),
		'section'     => 'lumiere_gallery_settings',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 50,
			'step' => 5,
		),
	) );

	// Enable Lightbox
	$wp_customize->add_setting( 'lumiere_enable_lightbox', array(
		'default'           => true,
		'sanitize_callback' => 'lumiere_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'lumiere_enable_lightbox', array(
		'label'   => __( 'Activer la lightbox', 'lumiere-portfolio' ),
		'section' => 'lumiere_gallery_settings',
		'type'    => 'checkbox',
	) );

	// Lazy Loading
	$wp_customize->add_setting( 'lumiere_lazy_loading', array(
		'default'           => true,
		'sanitize_callback' => 'lumiere_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'lumiere_lazy_loading', array(
		'label'   => __( 'Activer le lazy loading', 'lumiere-portfolio' ),
		'section' => 'lumiere_gallery_settings',
		'type'    => 'checkbox',
	) );

	// ===== SECTION: SOCIAL MEDIA =====
	$wp_customize->add_section( 'lumiere_social_media', array(
		'title'    => __( 'Réseaux sociaux', 'lumiere-portfolio' ),
		'priority' => 50,
	) );

	$social_networks = array(
		'instagram' => 'Instagram',
		'facebook'  => 'Facebook',
		'twitter'   => 'Twitter/X',
		'pinterest' => 'Pinterest',
		'behance'   => 'Behance',
		'500px'     => '500px',
		'flickr'    => 'Flickr',
	);

	foreach ( $social_networks as $key => $label ) {
		$wp_customize->add_setting( 'lumiere_social_' . $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( 'lumiere_social_' . $key, array(
			'label'   => $label . ' URL',
			'section' => 'lumiere_social_media',
			'type'    => 'url',
		) );
	}

	// ===== SECTION: PERFORMANCE =====
	$wp_customize->add_section( 'lumiere_performance', array(
		'title'    => __( 'Performance', 'lumiere-portfolio' ),
		'priority' => 55,
	) );

	// Enable WebP
	$wp_customize->add_setting( 'lumiere_enable_webp', array(
		'default'           => true,
		'sanitize_callback' => 'lumiere_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'lumiere_enable_webp', array(
		'label'       => __( 'Activer la conversion WebP', 'lumiere-portfolio' ),
		'description' => __( 'Convertir automatiquement les images en WebP pour de meilleures performances', 'lumiere-portfolio' ),
		'section'     => 'lumiere_performance',
		'type'        => 'checkbox',
	) );

	// Preload Images
	$wp_customize->add_setting( 'lumiere_preload_images', array(
		'default'           => '3',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'lumiere_preload_images', array(
		'label'       => __( 'Nombre d\'images à précharger', 'lumiere-portfolio' ),
		'section'     => 'lumiere_performance',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 10,
			'step' => 1,
		),
	) );
}
add_action( 'customize_register', 'lumiere_customize_register' );

/**
 * Sanitize checkbox
 */
function lumiere_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Output customizer CSS
 */
function lumiere_customizer_css() {
	$primary_color = get_theme_mod( 'lumiere_primary_color', '#1a1a1a' );
	$accent_color  = get_theme_mod( 'lumiere_accent_color', '#d4af37' );
	$bg_color      = get_theme_mod( 'lumiere_bg_color', '#ffffff' );
	$text_color    = get_theme_mod( 'lumiere_text_color', '#333333' );
	$font_size     = get_theme_mod( 'lumiere_font_size', 16 );
	$container_width = get_theme_mod( 'lumiere_container_width', 1200 );
	$gallery_gap   = get_theme_mod( 'lumiere_gallery_gap', 20 );

	?>
	<style type="text/css">
		:root {
			--color-primary: <?php echo esc_attr( $primary_color ); ?>;
			--color-accent: <?php echo esc_attr( $accent_color ); ?>;
			--color-bg: <?php echo esc_attr( $bg_color ); ?>;
			--color-text: <?php echo esc_attr( $text_color ); ?>;
			--font-size-base: <?php echo esc_attr( $font_size ); ?>px;
			--container-width: <?php echo esc_attr( $container_width ); ?>px;
			--gallery-gap: <?php echo esc_attr( $gallery_gap ); ?>px;
		}

		body {
			background-color: var(--color-bg);
			color: var(--color-text);
			font-size: var(--font-size-base);
		}

		a {
			color: var(--color-primary);
		}

		a:hover {
			color: var(--color-accent);
		}

		.container {
			max-width: var(--container-width);
		}

		.gallery-grid {
			gap: var(--gallery-gap);
		}

		.btn-primary {
			background-color: var(--color-primary);
		}

		.btn-primary:hover {
			background-color: var(--color-accent);
		}
	</style>
	<?php
}
add_action( 'wp_head', 'lumiere_customizer_css' );
