<?php
/**
 * Template Tags and Helper Functions
 *
 * @package Lumière_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display social media links
 */
function lumiere_social_links() {
	$social_networks = array(
		'instagram' => 'Instagram',
		'facebook'  => 'Facebook',
		'twitter'   => 'Twitter',
		'pinterest' => 'Pinterest',
		'behance'   => 'Behance',
		'500px'     => '500px',
		'flickr'    => 'Flickr',
	);

	$output = '<ul class="social-links">';
	foreach ( $social_networks as $key => $label ) {
		$url = get_theme_mod( 'lumiere_social_' . $key );
		if ( ! empty( $url ) ) {
			$output .= sprintf(
				'<li class="social-link social-link-%s"><a href="%s" target="_blank" rel="noopener" aria-label="%s"><i class="icon-%s"></i></a></li>',
				esc_attr( $key ),
				esc_url( $url ),
				esc_attr( $label ),
				esc_attr( $key )
			);
		}
	}
	$output .= '</ul>';

	echo $output;
}

/**
 * Display gallery meta (genre, session, location)
 */
function lumiere_gallery_meta( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$output = '<div class="gallery-meta">';

	// Genre
	$genres = get_the_terms( $post_id, 'lumiere_genre' );
	if ( $genres && ! is_wp_error( $genres ) ) {
		$output .= '<span class="meta-genre">';
		foreach ( $genres as $genre ) {
			$output .= '<a href="' . get_term_link( $genre ) . '">' . esc_html( $genre->name ) . '</a> ';
		}
		$output .= '</span>';
	}

	// Session
	$sessions = get_the_terms( $post_id, 'lumiere_session' );
	if ( $sessions && ! is_wp_error( $sessions ) ) {
		$output .= '<span class="meta-session">';
		foreach ( $sessions as $session ) {
			$output .= '<a href="' . get_term_link( $session ) . '">' . esc_html( $session->name ) . '</a> ';
		}
		$output .= '</span>';
	}

	// Location
	$locations = get_the_terms( $post_id, 'lumiere_location' );
	if ( $locations && ! is_wp_error( $locations ) ) {
		$output .= '<span class="meta-location">';
		foreach ( $locations as $location ) {
			$output .= '<a href="' . get_term_link( $location ) . '">' . esc_html( $location->name ) . '</a> ';
		}
		$output .= '</span>';
	}

	$output .= '</div>';

	echo $output;
}

/**
 * Get gallery images
 */
function lumiere_get_gallery_images( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$gallery_images = get_post_meta( $post_id, '_lumiere_gallery_images', true );
	
	if ( empty( $gallery_images ) ) {
		return array();
	}

	$image_ids = explode( ',', $gallery_images );
	$images = array();

	foreach ( $image_ids as $image_id ) {
		$image_id = absint( $image_id );
		if ( $image_id ) {
			$images[] = array(
				'id'       => $image_id,
				'url'      => wp_get_attachment_url( $image_id ),
				'thumb'    => wp_get_attachment_image_url( $image_id, 'portfolio-thumb' ),
				'medium'   => wp_get_attachment_image_url( $image_id, 'portfolio-medium' ),
				'large'    => wp_get_attachment_image_url( $image_id, 'portfolio-large' ),
				'full'     => wp_get_attachment_url( $image_id ),
				'alt'      => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
				'caption'  => wp_get_attachment_caption( $image_id ),
			);
		}
	}

	return $images;
}

/**
 * Display breadcrumbs
 */
function lumiere_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$output = '<nav class="breadcrumbs" aria-label="Breadcrumb">';
	$output .= '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';

	// Home
	$output .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
	$output .= '<a itemprop="item" href="' . home_url( '/' ) . '"><span itemprop="name">Accueil</span></a>';
	$output .= '<meta itemprop="position" content="1" />';
	$output .= '</li>';

	$position = 2;

	if ( is_archive() ) {
		$output .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		$output .= '<span itemprop="name">' . get_the_archive_title() . '</span>';
		$output .= '<meta itemprop="position" content="' . $position . '" />';
		$output .= '</li>';
	} elseif ( is_single() ) {
		$post_type = get_post_type();
		
		if ( 'lumiere_gallery' === $post_type ) {
			$output .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
			$output .= '<a itemprop="item" href="' . get_post_type_archive_link( $post_type ) . '"><span itemprop="name">Galeries</span></a>';
			$output .= '<meta itemprop="position" content="' . $position++ . '" />';
			$output .= '</li>';
		}

		$output .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		$output .= '<span itemprop="name">' . get_the_title() . '</span>';
		$output .= '<meta itemprop="position" content="' . $position . '" />';
		$output .= '</li>';
	} elseif ( is_page() ) {
		$output .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		$output .= '<span itemprop="name">' . get_the_title() . '</span>';
		$output .= '<meta itemprop="position" content="' . $position . '" />';
		$output .= '</li>';
	}

	$output .= '</ol></nav>';

	echo $output;
}

/**
 * Display post thumbnail with responsive srcset
 */
function lumiere_post_thumbnail( $size = 'portfolio-grid', $attr = array() ) {
	if ( ! has_post_thumbnail() ) {
		return;
	}

	$default_attr = array(
		'loading' => 'lazy',
		'class'   => 'gallery-thumbnail',
	);

	$attr = wp_parse_args( $attr, $default_attr );

	the_post_thumbnail( $size, $attr );
}

/**
 * Get reading time
 */
function lumiere_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$word_count = str_word_count( strip_tags( $content ) );
	$reading_time = ceil( $word_count / 200 );

	return $reading_time;
}

/**
 * Display reading time
 */
function lumiere_display_reading_time() {
	$time = lumiere_reading_time();
	printf(
		'<span class="reading-time">%d min de lecture</span>',
		$time
	);
}

/**
 * Check if gallery has lightbox enabled
 */
function lumiere_has_lightbox( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$lightbox = get_post_meta( $post_id, '_lumiere_gallery_lightbox', true );
	
	// Check global setting if not set for this gallery
	if ( empty( $lightbox ) ) {
		return get_theme_mod( 'lumiere_enable_lightbox', true );
	}

	return '1' === $lightbox;
}

/**
 * Display copyright text
 */
function lumiere_copyright() {
	$copyright_text = get_theme_mod( 'lumiere_copyright_text', '' );
	
	if ( empty( $copyright_text ) ) {
		$copyright_text = sprintf(
			'&copy; %s %s. Tous droits réservés.',
			date( 'Y' ),
			get_bloginfo( 'name' )
		);
	}

	echo '<p class="copyright-text">' . wp_kses_post( $copyright_text ) . '</p>';
}

/**
 * Get related galleries
 */
function lumiere_get_related_galleries( $post_id = null, $limit = 3 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get current gallery genres
	$genres = wp_get_post_terms( $post_id, 'lumiere_genre', array( 'fields' => 'ids' ) );

	if ( empty( $genres ) ) {
		return array();
	}

	$args = array(
		'post_type'      => 'lumiere_gallery',
		'posts_per_page' => $limit,
		'post__not_in'   => array( $post_id ),
		'tax_query'      => array(
			array(
				'taxonomy' => 'lumiere_genre',
				'field'    => 'term_id',
				'terms'    => $genres,
			),
		),
	);

	return new WP_Query( $args );
}

/**
 * Display related galleries
 */
function lumiere_related_galleries( $post_id = null, $limit = 3 ) {
	$related = lumiere_get_related_galleries( $post_id, $limit );

	if ( ! $related->have_posts() ) {
		return;
	}

	echo '<section class="related-galleries">';
	echo '<h2>Galeries similaires</h2>';
	echo '<div class="gallery-grid gallery-grid-3">';

	while ( $related->have_posts() ) {
		$related->the_post();
		get_template_part( 'parts/gallery-item' );
	}

	echo '</div></section>';

	wp_reset_postdata();
}
