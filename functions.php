<?php
/**
 * Lumière Portfolio - Functions and Definitions
 * 
 * Premium WordPress theme for photographers
 * 
 * @package Lumière_Portfolio
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// ===== THEME CONSTANTS =====
if ( ! defined( 'LUMIERE_VERSION' ) ) {
	define( 'LUMIERE_VERSION', '2.0.0' );
}

if ( ! defined( 'LUMIERE_DIR' ) ) {
	define( 'LUMIERE_DIR', get_template_directory() );
}

if ( ! defined( 'LUMIERE_URI' ) ) {
	define( 'LUMIERE_URI', get_template_directory_uri() );
}

// ===== LOAD THEME MODULES =====
$modules = array(
	'inc/bootstrap.php',
	'inc/post-types.php',
	'inc/taxonomies.php',
	'inc/customizer.php',
	'inc/ajax-handlers.php',
	'inc/image-optimization.php',
	'inc/contact-form.php',
);

foreach ( $modules as $module ) {
	if ( file_exists( LUMIERE_DIR . '/' . $module ) ) {
		require_once LUMIERE_DIR . '/' . $module;
	}
}

/**
 * Theme Setup
 * Configure theme features and support
 */
function lumiere_setup() {
	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Navigation menus
	register_nav_menus( array(
		'header_menu' => __( 'Menu principal', 'lumiere-portfolio' ),
		'footer_menu' => __( 'Menu pied de page', 'lumiere-portfolio' ),
		'social_menu' => __( 'Menu réseaux sociaux', 'lumiere-portfolio' ),
	) );

	// HTML5 markup
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Gutenberg features
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	// Custom background
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );

	// Selective refresh for widgets
	add_theme_support( 'customize-selective-refresh-widgets' );

	// WooCommerce support (for future e-commerce features)
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// RSS feed links
	add_theme_support( 'automatic-feed-links' );

	// Set content width
	if ( ! isset( $content_width ) ) {
		$content_width = 1200;
	}
}
add_action( 'after_setup_theme', 'lumiere_setup' );

/**
 * Register Widget Areas
 */
function lumiere_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Pied de page 1', 'lumiere-portfolio' ),
		'id'            => 'footer-1',
		'description'   => __( 'Première colonne du pied de page', 'lumiere-portfolio' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Pied de page 2', 'lumiere-portfolio' ),
		'id'            => 'footer-2',
		'description'   => __( 'Deuxième colonne du pied de page', 'lumiere-portfolio' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Pied de page 3', 'lumiere-portfolio' ),
		'id'            => 'footer-3',
		'description'   => __( 'Troisième colonne du pied de page', 'lumiere-portfolio' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'lumiere_widgets_init' );

/**
 * Enqueue Scripts and Styles
 */
function lumiere_enqueue_assets() {
	// Google Fonts
	$heading_font = get_theme_mod( 'lumiere_heading_font', 'Playfair Display' );
	$body_font = get_theme_mod( 'lumiere_body_font', 'Inter' );
	
	wp_enqueue_style(
		'lumiere-google-fonts',
		'https://fonts.googleapis.com/css2?family=' . urlencode( $heading_font ) . ':wght@400;500;600;700&family=' . urlencode( $body_font ) . ':wght@300;400;500;600&display=swap',
		array(),
		LUMIERE_VERSION
	);

	// Main stylesheet
	wp_enqueue_style(
		'lumiere-main',
		LUMIERE_URI . '/assets/css/main.css',
		array(),
		LUMIERE_VERSION
	);

	// Gallery styles
	wp_enqueue_style(
		'lumiere-gallery',
		LUMIERE_URI . '/assets/css/gallery.css',
		array( 'lumiere-main' ),
		LUMIERE_VERSION
	);

	// Lightbox styles
	if ( get_theme_mod( 'lumiere_enable_lightbox', true ) ) {
		wp_enqueue_style(
			'lumiere-lightbox',
			LUMIERE_URI . '/assets/css/lightbox.css',
			array( 'lumiere-main' ),
			LUMIERE_VERSION
		);
	}

	// Main JavaScript
	wp_enqueue_script(
		'lumiere-main',
		LUMIERE_URI . '/assets/js/main.js',
		array( 'jquery' ),
		LUMIERE_VERSION,
		true
	);

	// Gallery filter
	wp_enqueue_script(
		'lumiere-gallery-filter',
		LUMIERE_URI . '/assets/js/gallery-filter.js',
		array( 'jquery', 'lumiere-main' ),
		LUMIERE_VERSION,
		true
	);

	// Lightbox
	if ( get_theme_mod( 'lumiere_enable_lightbox', true ) ) {
		wp_enqueue_script(
			'lumiere-lightbox',
			LUMIERE_URI . '/assets/js/lightbox.js',
			array( 'jquery' ),
			LUMIERE_VERSION,
			true
		);
	}

	// Localize scripts
	wp_localize_script( 'lumiere-main', 'lumiereSettings', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'siteUrl' => home_url(),
		'themeUrl' => LUMIERE_URI,
	) );

	// Remove unnecessary scripts
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_enqueue_scripts', 'lumiere_enqueue_assets' );

/**
 * Admin Scripts and Styles
 */
function lumiere_admin_scripts( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}

	// Admin styles for gallery meta boxes
	wp_enqueue_style(
		'lumiere-admin',
		LUMIERE_URI . '/assets/css/admin.css',
		array(),
		LUMIERE_VERSION
	);

	// Media uploader
	wp_enqueue_media();

	// Admin JavaScript
	wp_enqueue_script(
		'lumiere-admin',
		LUMIERE_URI . '/assets/js/admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		LUMIERE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'lumiere_admin_scripts' );

/**
 * Performance Optimizations
 */
function lumiere_performance_optimizations() {
	// Remove WordPress version
	remove_action( 'wp_head', 'wp_generator' );
	
	// Remove RSD link
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove Windows Live Writer link
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove REST API link
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	
	// Remove oEmbed discovery links
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	
	// Remove shortlink
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	
	// Disable emoji scripts
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'lumiere_performance_optimizations' );

/**
 * Disable Gutenberg for certain post types
 */
function lumiere_disable_gutenberg( $use_block_editor, $post_type ) {
	if ( 'lumiere_gallery' === $post_type ) {
		return false;
	}
	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'lumiere_disable_gutenberg', 10, 2 );

/**
 * Custom excerpt length
 */
function lumiere_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'lumiere_excerpt_length' );

/**
 * Custom excerpt more
 */
function lumiere_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'lumiere_excerpt_more' );

/**
 * Add custom body classes
 */
function lumiere_body_classes( $classes ) {
	// Add class for layout
	$layout = get_theme_mod( 'lumiere_site_layout', 'full-width' );
	$classes[] = 'layout-' . $layout;
	
	// Add class if lightbox is enabled
	if ( get_theme_mod( 'lumiere_enable_lightbox', true ) ) {
		$classes[] = 'has-lightbox';
	}
	
	// Add singular class
	if ( is_singular() ) {
		$classes[] = 'singular-' . get_post_type();
	}
	
	return $classes;
}
add_filter( 'body_class', 'lumiere_body_classes' );

/**
 * Custom template tags and helper functions
 */
require_once LUMIERE_DIR . '/inc/template-tags.php';
