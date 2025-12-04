<?php
/**
 * Lumière Portfolio functions and definitions
 *
 * @package Lumiere_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// THEME CONSTANTS
if ( ! defined( 'LUMIERE_VERSION' ) ) {
	define( 'LUMIERE_VERSION', '1.0.0' );
}

if ( ! defined( 'LUMIERE_DIR' ) ) {
	define( 'LUMIERE_DIR', get_template_directory() );
}

if ( ! defined( 'LUMIERE_URI' ) ) {
	define( 'LUMIERE_URI', get_template_directory_uri() );
}

// Bootstrap includes
if ( file_exists( LUMIERE_DIR . '/inc/bootstrap.php' ) ) {
	require_once LUMIERE_DIR . '/inc/bootstrap.php';
}

/**
 * Theme setup
 */
function lumiere_setup() {
	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Support for post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Custom logo support.
	add_theme_support( 'custom-logo', array(
		'height'      => 40,
		'width'       => 160,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Menus.
	register_nav_menus( array(
		'header_menu' => __( 'Header Menu', 'lumiere-portfolio' ),
		'footer_menu' => __( 'Footer Menu', 'lumiere-portfolio' ),
	) );

	// HTML5 markup support.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	// Gutenberg support.
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );

	// Elementor compatibility: use theme styles.
	add_theme_support( 'elementor-default-kit' );

	// Featured image sizes.
	add_image_size( 'portfolio-large', 2000, 1333, true );
	add_image_size( 'portfolio-grid', 1200, 800, true );
	add_image_size( 'portfolio-thumb', 600, 400, true );
}
add_action( 'after_setup_theme', 'lumiere_setup' );

/**
 * Register widget areas (sidebars)
 */
function lumiere_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Widgets', 'lumiere-portfolio' ),
		'id'            => 'footer-1',
		'description'   => __( 'Widgets affichés dans le pied de page.', 'lumiere-portfolio' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'lumiere_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function lumiere_enqueue_assets() {
	// Google Fonts.
	wp_enqueue_style(
		'lumiere-google-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap',
		array(),
		LUMIERE_VERSION
	);

	// Main stylesheet.
	wp_enqueue_style(
		'lumiere-main',
		LUMIERE_URI . '/assets/css/main.css',
		array(),
		LUMIERE_VERSION
	);

	// Deregister WP-embed if not needed (performance).
	wp_deregister_script( 'wp-embed' );

	// Main JS.
	wp_enqueue_script(
		'lumiere-main',
		LUMIERE_URI . '/assets/js/main.js',
		array( 'jquery' ),
		LUMIERE_VERSION,
		true
	);

	// Pass data to JS.
	wp_localize_script( 'lumiere-main', 'lumiereSettings', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'lumiere_enqueue_assets' );

/**
 * Register Custom Post Type: Galleries
 */
function lumiere_register_cpt_galleries() {
	$labels = array(
		'name'               => __( 'Galeries', 'lumiere-portfolio' ),
		'singular_name'      => __( 'Galerie', 'lumiere-portfolio' ),
		'add_new'            => __( 'Ajouter une nouvelle', 'lumiere-portfolio' ),
		'add_new_item'       => __( 'Ajouter une nouvelle galerie', 'lumiere-portfolio' ),
		'edit_item'          => __( 'Modifier la galerie', 'lumiere-portfolio' ),
		'new_item'           => __( 'Nouvelle galerie', 'lumiere-portfolio' ),
		'all_items'          => __( 'Toutes les galeries', 'lumiere-portfolio' ),
		'view_item'          => __( 'Voir la galerie', 'lumiere-portfolio' ),
		'search_items'       => __( 'Rechercher des galeries', 'lumiere-portfolio' ),
		'not_found'          => __( 'Aucune galerie trouvée', 'lumiere-portfolio' ),
		'not_found_in_trash' => __( 'Aucune galerie dans la corbeille', 'lumiere-portfolio' ),
		'menu_name'          => __( 'Galeries', 'lumiere-portfolio' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-format-gallery',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'            => array( 'slug' => 'galeries' ),
	);

	register_post_type( 'lumiere_gallery', $args );
}
add_action( 'init', 'lumiere_register_cpt_galleries' );

/**
 * Register taxonomies: Genres, Sessions
 */
function lumiere_register_taxonomies() {
	// Genres (Portrait, Mariage, Street, etc.).
	$genre_labels = array(
		'name'          => __( 'Genres', 'lumiere-portfolio' ),
		'singular_name' => __( 'Genre', 'lumiere-portfolio' ),
	);

	register_taxonomy( 'lumiere_genre', 'lumiere_gallery', array(
		'hierarchical'      => true,
		'labels'            => $genre_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'genre' ),
	) );

	// Sessions (Studio, Extérieur, Nuit, etc.).
	$session_labels = array(
		'name'          => __( 'Sessions', 'lumiere-portfolio' ),
		'singular_name' => __( 'Session', 'lumiere-portfolio' ),
	);

	register_taxonomy( 'lumiere_session', 'lumiere_gallery', array(
		'hierarchical'      => false,
		'labels'            => $session_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'session' ),
	) );
}
add_action( 'init', 'lumiere_register_taxonomies' );

/**
 * Clean up head for performance/SEO
 */
function lumiere_cleanup_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'lumiere_cleanup_head' );

/**
 * Add loading="lazy" by default for images
 */
function lumiere_add_lazy_loading( $attr, $attachment, $size ) {
	$attr['loading'] = 'lazy';
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'lumiere_add_lazy_loading', 10, 3 );

/**
 * Include template tags / helpers if needed later
 */
// require_once LUMIERE_DIR . '/inc/template-tags.php';
