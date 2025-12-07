<?php
/**
 * Image Optimization Functions
 *
 * @package Lumière_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enable WebP support
 */
function lumiere_enable_webp_upload( $mimes ) {
	$mimes['webp'] = 'image/webp';
	return $mimes;
}
add_filter( 'upload_mimes', 'lumiere_enable_webp_upload' );

/**
 * Add WebP to allowed file types
 */
function lumiere_file_is_displayable_image( $result, $path ) {
	if ( false === $result ) {
		$info = @getimagesize( $path );
		if ( ! empty( $info ) ) {
			$result = 'image/webp' === $info['mime'];
		}
	}
	return $result;
}
add_filter( 'file_is_displayable_image', 'lumiere_file_is_displayable_image', 10, 2 );

/**
 * Auto generate WebP versions of uploaded images
 */
function lumiere_generate_webp_on_upload( $metadata, $attachment_id ) {
	if ( ! get_theme_mod( 'lumiere_enable_webp', true ) ) {
		return $metadata;
	}

	$file = get_attached_file( $attachment_id );
	$upload_dir = wp_upload_dir();

	if ( ! file_exists( $file ) ) {
		return $metadata;
	}

	$file_type = wp_check_filetype( $file );
	$allowed_types = array( 'image/jpeg', 'image/png' );

	if ( ! in_array( $file_type['type'], $allowed_types ) ) {
		return $metadata;
	}

	// Generate WebP for main image
	lumiere_create_webp_image( $file );

	// Generate WebP for all sizes
	if ( isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
		foreach ( $metadata['sizes'] as $size => $size_data ) {
			$size_file = path_join( dirname( $file ), $size_data['file'] );
			if ( file_exists( $size_file ) ) {
				lumiere_create_webp_image( $size_file );
			}
		}
	}

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'lumiere_generate_webp_on_upload', 10, 2 );

/**
 * Create WebP version of an image
 */
function lumiere_create_webp_image( $file ) {
	$file_type = wp_check_filetype( $file );
	$webp_file = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file );

	if ( file_exists( $webp_file ) ) {
		return $webp_file;
	}

	$image = null;

	switch ( $file_type['type'] ) {
		case 'image/jpeg':
			$image = @imagecreatefromjpeg( $file );
			break;
		case 'image/png':
			$image = @imagecreatefrompng( $file );
			imagepalettetotruecolor( $image );
			imagealphablending( $image, true );
			imagesavealpha( $image, true );
			break;
	}

	if ( $image && function_exists( 'imagewebp' ) ) {
		imagewebp( $image, $webp_file, 85 ); // 85% quality
		imagedestroy( $image );
		return $webp_file;
	}

	return false;
}

/**
 * Serve WebP images when available
 */
function lumiere_serve_webp_images( $html, $attachment_id, $size, $icon, $attr ) {
	if ( ! get_theme_mod( 'lumiere_enable_webp', true ) ) {
		return $html;
	}

	$file = get_attached_file( $attachment_id );
	$webp_file = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file );

	if ( file_exists( $webp_file ) ) {
		$webp_url = preg_replace( '/\.(jpe?g|png)$/i', '.webp', wp_get_attachment_url( $attachment_id ) );
		$original_url = wp_get_attachment_url( $attachment_id );
		
		// Use picture element for WebP with fallback
		$html = sprintf(
			'<picture><source srcset="%s" type="image/webp"><img src="%s" alt="%s" loading="lazy"></picture>',
			esc_url( $webp_url ),
			esc_url( $original_url ),
			esc_attr( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) )
		);
	}

	return $html;
}
add_filter( 'wp_get_attachment_image', 'lumiere_serve_webp_images', 10, 5 );

/**
 * Add responsive images srcset
 */
function lumiere_responsive_images( $attr, $attachment, $size ) {
	$image_meta = wp_get_attachment_metadata( $attachment->ID );
	
	if ( empty( $image_meta['sizes'] ) ) {
		return $attr;
	}

	$srcset = array();
	$sizes = array();

	// Generate srcset
	foreach ( $image_meta['sizes'] as $size_name => $size_data ) {
		$image_src = wp_get_attachment_image_src( $attachment->ID, $size_name );
		if ( $image_src ) {
			$srcset[] = $image_src[0] . ' ' . $image_src[1] . 'w';
		}
	}

	if ( ! empty( $srcset ) ) {
		$attr['srcset'] = implode( ', ', $srcset );
		$attr['sizes'] = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'lumiere_responsive_images', 10, 3 );

/**
 * Add lazy loading to images
 */
function lumiere_add_lazy_loading( $attr, $attachment, $size ) {
	if ( ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}
	
	// Add decode async for better performance
	$attr['decoding'] = 'async';
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'lumiere_add_lazy_loading', 10, 3 );

/**
 * Optimize image quality on upload
 */
function lumiere_optimize_image_quality( $image ) {
	$quality = get_theme_mod( 'lumiere_image_quality', 85 );
	return min( $quality, 90 ); // Max 90% to balance quality and size
}
add_filter( 'wp_editor_set_quality', 'lumiere_optimize_image_quality' );
add_filter( 'jpeg_quality', 'lumiere_optimize_image_quality' );

/**
 * Generate multiple image sizes for portfolio
 */
function lumiere_custom_image_sizes() {
	// Thumbnail sizes for different layouts
	add_image_size( 'portfolio-tiny', 300, 300, true );
	add_image_size( 'portfolio-small', 600, 600, true );
	add_image_size( 'portfolio-medium', 1200, 900, true );
	add_image_size( 'portfolio-large', 1920, 1440, false );
	add_image_size( 'portfolio-full', 2560, 1920, false );
	
	// Special sizes for different use cases
	add_image_size( 'portfolio-square', 800, 800, true );
	add_image_size( 'portfolio-wide', 1600, 900, true );
	add_image_size( 'portfolio-portrait', 900, 1200, true );
}
add_action( 'after_setup_theme', 'lumiere_custom_image_sizes' );

/**
 * Add custom image sizes to media library
 */
function lumiere_custom_image_sizes_names( $sizes ) {
	return array_merge( $sizes, array(
		'portfolio-tiny'     => __( 'Portfolio - Très petit', 'lumiere-portfolio' ),
		'portfolio-small'    => __( 'Portfolio - Petit', 'lumiere-portfolio' ),
		'portfolio-medium'   => __( 'Portfolio - Moyen', 'lumiere-portfolio' ),
		'portfolio-large'    => __( 'Portfolio - Grand', 'lumiere-portfolio' ),
		'portfolio-full'     => __( 'Portfolio - Très grand', 'lumiere-portfolio' ),
		'portfolio-square'   => __( 'Portfolio - Carré', 'lumiere-portfolio' ),
		'portfolio-wide'     => __( 'Portfolio - Panoramique', 'lumiere-portfolio' ),
		'portfolio-portrait' => __( 'Portfolio - Portrait', 'lumiere-portfolio' ),
	) );
}
add_filter( 'image_size_names_choose', 'lumiere_custom_image_sizes_names' );

/**
 * Preload critical images
 */
function lumiere_preload_critical_images() {
	if ( ! is_front_page() && ! is_archive() ) {
		return;
	}

	$preload_count = get_theme_mod( 'lumiere_preload_images', 3 );
	
	$args = array(
		'post_type'      => 'lumiere_gallery',
		'posts_per_page' => $preload_count,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			if ( has_post_thumbnail() ) {
				$image_url = get_the_post_thumbnail_url( get_the_ID(), 'portfolio-medium' );
				if ( $image_url ) {
					echo '<link rel="preload" as="image" href="' . esc_url( $image_url ) . '">' . "\n";
				}
			}
		}
		wp_reset_postdata();
	}
}
add_action( 'wp_head', 'lumiere_preload_critical_images', 1 );

/**
 * Add image compression notice in media library
 */
function lumiere_image_compression_notice( $form_fields, $post ) {
	$file = get_attached_file( $post->ID );
	$webp_file = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file );
	
	if ( file_exists( $webp_file ) ) {
		$original_size = filesize( $file );
		$webp_size = filesize( $webp_file );
		$savings = round( ( 1 - ( $webp_size / $original_size ) ) * 100 );
		
		$form_fields['lumiere_webp_info'] = array(
			'label' => __( 'WebP Optimisation', 'lumiere-portfolio' ),
			'input' => 'html',
			'html'  => sprintf(
				'<span style="color: green;">✓ WebP généré (%d%% de réduction)</span>',
				$savings
			),
		);
	}
	
	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'lumiere_image_compression_notice', 10, 2 );
