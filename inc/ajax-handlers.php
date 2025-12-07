<?php
/**
 * AJAX Handlers for Gallery Filtering and Dynamic Content
 *
 * @package Lumière_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax handler for filtering galleries
 */
function lumiere_ajax_filter_galleries() {
	check_ajax_referer( 'lumiere_filter_nonce', 'nonce' );

	$genre    = isset( $_POST['genre'] ) ? sanitize_text_field( $_POST['genre'] ) : '';
	$session  = isset( $_POST['session'] ) ? sanitize_text_field( $_POST['session'] ) : '';
	$location = isset( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : '';
	$paged    = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
	$layout   = isset( $_POST['layout'] ) ? sanitize_text_field( $_POST['layout'] ) : 'grid';

	$args = array(
		'post_type'      => 'lumiere_gallery',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	// Build tax query
	$tax_query = array( 'relation' => 'AND' );

	if ( ! empty( $genre ) && 'all' !== $genre ) {
		$tax_query[] = array(
			'taxonomy' => 'lumiere_genre',
			'field'    => 'slug',
			'terms'    => $genre,
		);
	}

	if ( ! empty( $session ) && 'all' !== $session ) {
		$tax_query[] = array(
			'taxonomy' => 'lumiere_session',
			'field'    => 'slug',
			'terms'    => $session,
		);
	}

	if ( ! empty( $location ) && 'all' !== $location ) {
		$tax_query[] = array(
			'taxonomy' => 'lumiere_location',
			'field'    => 'slug',
			'terms'    => $location,
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'parts/gallery-item', $layout );
		}
		wp_reset_postdata();
	} else {
		echo '<div class="no-results">';
		echo '<p>' . __( 'Aucune galerie trouvée.', 'lumiere-portfolio' ) . '</p>';
		echo '</div>';
	}

	$content = ob_get_clean();

	wp_send_json_success( array(
		'content'      => $content,
		'found_posts'  => $query->found_posts,
		'max_pages'    => $query->max_num_pages,
		'current_page' => $paged,
	) );
}
add_action( 'wp_ajax_lumiere_filter_galleries', 'lumiere_ajax_filter_galleries' );
add_action( 'wp_ajax_nopriv_lumiere_filter_galleries', 'lumiere_ajax_filter_galleries' );

/**
 * Ajax handler for loading more galleries (infinite scroll)
 */
function lumiere_ajax_load_more_galleries() {
	check_ajax_referer( 'lumiere_load_more_nonce', 'nonce' );

	$paged  = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
	$layout = isset( $_POST['layout'] ) ? sanitize_text_field( $_POST['layout'] ) : 'grid';

	$args = array(
		'post_type'      => 'lumiere_gallery',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'post_status'    => 'publish',
	);

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'parts/gallery-item', $layout );
		}
		wp_reset_postdata();
	}

	$content = ob_get_clean();

	wp_send_json_success( array(
		'content'     => $content,
		'has_more'    => $paged < $query->max_num_pages,
		'next_page'   => $paged + 1,
	) );
}
add_action( 'wp_ajax_lumiere_load_more_galleries', 'lumiere_ajax_load_more_galleries' );
add_action( 'wp_ajax_nopriv_lumiere_load_more_galleries', 'lumiere_ajax_load_more_galleries' );

/**
 * Ajax handler for search galleries
 */
function lumiere_ajax_search_galleries() {
	check_ajax_referer( 'lumiere_search_nonce', 'nonce' );

	$search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

	if ( empty( $search ) ) {
		wp_send_json_error( array( 'message' => __( 'Veuillez entrer un terme de recherche.', 'lumiere-portfolio' ) ) );
	}

	$args = array(
		'post_type'      => 'lumiere_gallery',
		'posts_per_page' => 20,
		's'              => $search,
		'post_status'    => 'publish',
	);

	$query = new WP_Query( $args );

	$results = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$results[] = array(
				'id'        => get_the_ID(),
				'title'     => get_the_title(),
				'url'       => get_permalink(),
				'excerpt'   => get_the_excerpt(),
				'thumbnail' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
			);
		}
		wp_reset_postdata();
	}

	wp_send_json_success( array(
		'results' => $results,
		'count'   => count( $results ),
	) );
}
add_action( 'wp_ajax_lumiere_search_galleries', 'lumiere_ajax_search_galleries' );
add_action( 'wp_ajax_nopriv_lumiere_search_galleries', 'lumiere_ajax_search_galleries' );

/**
 * Ajax handler for getting gallery details
 */
function lumiere_ajax_get_gallery_details() {
	check_ajax_referer( 'lumiere_gallery_nonce', 'nonce' );

	$gallery_id = isset( $_POST['gallery_id'] ) ? absint( $_POST['gallery_id'] ) : 0;

	if ( ! $gallery_id ) {
		wp_send_json_error( array( 'message' => __( 'ID de galerie invalide.', 'lumiere-portfolio' ) ) );
	}

	$gallery_images = get_post_meta( $gallery_id, '_lumiere_gallery_images', true );
	$image_ids = ! empty( $gallery_images ) ? explode( ',', $gallery_images ) : array();

	$images = array();
	foreach ( $image_ids as $image_id ) {
		$image_id = absint( $image_id );
		if ( $image_id ) {
			$images[] = array(
				'id'       => $image_id,
				'url'      => wp_get_attachment_url( $image_id ),
				'thumb'    => wp_get_attachment_image_url( $image_id, 'portfolio-thumb' ),
				'medium'   => wp_get_attachment_image_url( $image_id, 'portfolio-grid' ),
				'large'    => wp_get_attachment_image_url( $image_id, 'portfolio-large' ),
				'full'     => wp_get_attachment_url( $image_id ),
				'alt'      => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
				'caption'  => wp_get_attachment_caption( $image_id ),
			);
		}
	}

	wp_send_json_success( array(
		'title'  => get_the_title( $gallery_id ),
		'images' => $images,
		'count'  => count( $images ),
	) );
}
add_action( 'wp_ajax_lumiere_get_gallery_details', 'lumiere_ajax_get_gallery_details' );
add_action( 'wp_ajax_nopriv_lumiere_get_gallery_details', 'lumiere_ajax_get_gallery_details' );

/**
 * Register AJAX nonces in footer
 */
function lumiere_ajax_nonces() {
	?>
	<script type="text/javascript">
		var lumiereAjax = {
			ajaxUrl: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			filterNonce: '<?php echo wp_create_nonce( 'lumiere_filter_nonce' ); ?>',
			loadMoreNonce: '<?php echo wp_create_nonce( 'lumiere_load_more_nonce' ); ?>',
			searchNonce: '<?php echo wp_create_nonce( 'lumiere_search_nonce' ); ?>',
			galleryNonce: '<?php echo wp_create_nonce( 'lumiere_gallery_nonce' ); ?>',
		};
	</script>
	<?php
}
add_action( 'wp_footer', 'lumiere_ajax_nonces' );
