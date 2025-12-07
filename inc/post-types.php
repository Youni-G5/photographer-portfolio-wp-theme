<?php
/**
 * Custom Post Types Registration
 *
 * @package Lumière_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Type: Galleries
 */
function lumiere_register_cpt_galleries() {
	$labels = array(
		'name'                  => __( 'Galeries', 'lumiere-portfolio' ),
		'singular_name'         => __( 'Galerie', 'lumiere-portfolio' ),
		'add_new'               => __( 'Ajouter une nouvelle', 'lumiere-portfolio' ),
		'add_new_item'          => __( 'Ajouter une nouvelle galerie', 'lumiere-portfolio' ),
		'edit_item'             => __( 'Modifier la galerie', 'lumiere-portfolio' ),
		'new_item'              => __( 'Nouvelle galerie', 'lumiere-portfolio' ),
		'all_items'             => __( 'Toutes les galeries', 'lumiere-portfolio' ),
		'view_item'             => __( 'Voir la galerie', 'lumiere-portfolio' ),
		'search_items'          => __( 'Rechercher des galeries', 'lumiere-portfolio' ),
		'not_found'             => __( 'Aucune galerie trouvée', 'lumiere-portfolio' ),
		'not_found_in_trash'    => __( 'Aucune galerie dans la corbeille', 'lumiere-portfolio' ),
		'menu_name'             => __( 'Galeries', 'lumiere-portfolio' ),
		'featured_image'        => __( 'Image mise en avant', 'lumiere-portfolio' ),
		'set_featured_image'    => __( 'Définir l\'image mise en avant', 'lumiere-portfolio' ),
		'remove_featured_image' => __( 'Retirer l\'image mise en avant', 'lumiere-portfolio' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'has_archive'         => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-gallery',
		'show_in_rest'        => true,
		'rest_base'           => 'galleries',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
		'rewrite'             => array( 
			'slug'       => 'galeries',
			'with_front' => false,
		),
		'capability_type'     => 'post',
		'hierarchical'        => false,
	);

	register_post_type( 'lumiere_gallery', $args );
}
add_action( 'init', 'lumiere_register_cpt_galleries' );

/**
 * Register Custom Post Type: Testimonials
 */
function lumiere_register_cpt_testimonials() {
	$labels = array(
		'name'               => __( 'Témoignages', 'lumiere-portfolio' ),
		'singular_name'      => __( 'Témoignage', 'lumiere-portfolio' ),
		'add_new'            => __( 'Ajouter un nouveau', 'lumiere-portfolio' ),
		'add_new_item'       => __( 'Ajouter un nouveau témoignage', 'lumiere-portfolio' ),
		'edit_item'          => __( 'Modifier le témoignage', 'lumiere-portfolio' ),
		'new_item'           => __( 'Nouveau témoignage', 'lumiere-portfolio' ),
		'all_items'          => __( 'Tous les témoignages', 'lumiere-portfolio' ),
		'view_item'          => __( 'Voir le témoignage', 'lumiere-portfolio' ),
		'search_items'       => __( 'Rechercher des témoignages', 'lumiere-portfolio' ),
		'not_found'          => __( 'Aucun témoignage trouvé', 'lumiere-portfolio' ),
		'not_found_in_trash' => __( 'Aucun témoignage dans la corbeille', 'lumiere-portfolio' ),
		'menu_name'          => __( 'Témoignages', 'lumiere-portfolio' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-testimonial',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'            => array( 'slug' => 'testimonials' ),
	);

	register_post_type( 'lumiere_testimonial', $args );
}
add_action( 'init', 'lumiere_register_cpt_testimonials' );

/**
 * Add custom meta boxes for galleries
 */
function lumiere_add_gallery_meta_boxes() {
	add_meta_box(
		'lumiere_gallery_images',
		__( 'Images de la galerie', 'lumiere-portfolio' ),
		'lumiere_gallery_images_callback',
		'lumiere_gallery',
		'normal',
		'high'
	);

	add_meta_box(
		'lumiere_gallery_settings',
		__( 'Paramètres de la galerie', 'lumiere-portfolio' ),
		'lumiere_gallery_settings_callback',
		'lumiere_gallery',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'lumiere_add_gallery_meta_boxes' );

/**
 * Gallery images meta box callback
 */
function lumiere_gallery_images_callback( $post ) {
	wp_nonce_field( 'lumiere_gallery_images', 'lumiere_gallery_images_nonce' );
	$gallery_images = get_post_meta( $post->ID, '_lumiere_gallery_images', true );
	?>
	<div id="lumiere-gallery-images-container">
		<button type="button" class="button lumiere-upload-gallery-button">
			<?php _e( 'Ajouter des images', 'lumiere-portfolio' ); ?>
		</button>
		<input type="hidden" name="lumiere_gallery_images" id="lumiere_gallery_images" value="<?php echo esc_attr( $gallery_images ); ?>" />
		<ul class="lumiere-gallery-images-list">
			<!-- Images will be added here via JavaScript -->
		</ul>
	</div>
	<?php
}

/**
 * Gallery settings meta box callback
 */
function lumiere_gallery_settings_callback( $post ) {
	wp_nonce_field( 'lumiere_gallery_settings', 'lumiere_gallery_settings_nonce' );
	
	$layout = get_post_meta( $post->ID, '_lumiere_gallery_layout', true );
	$columns = get_post_meta( $post->ID, '_lumiere_gallery_columns', true );
	$lightbox = get_post_meta( $post->ID, '_lumiere_gallery_lightbox', true );
	
	?>
	<p>
		<label for="lumiere_gallery_layout"><?php _e( 'Mise en page', 'lumiere-portfolio' ); ?></label>
		<select name="lumiere_gallery_layout" id="lumiere_gallery_layout" class="widefat">
			<option value="grid" <?php selected( $layout, 'grid' ); ?>><?php _e( 'Grille', 'lumiere-portfolio' ); ?></option>
			<option value="masonry" <?php selected( $layout, 'masonry' ); ?>><?php _e( 'Masonry', 'lumiere-portfolio' ); ?></option>
			<option value="slider" <?php selected( $layout, 'slider' ); ?>><?php _e( 'Slider', 'lumiere-portfolio' ); ?></option>
			<option value="justified" <?php selected( $layout, 'justified' ); ?>><?php _e( 'Justifié', 'lumiere-portfolio' ); ?></option>
		</select>
	</p>
	
	<p>
		<label for="lumiere_gallery_columns"><?php _e( 'Colonnes', 'lumiere-portfolio' ); ?></label>
		<select name="lumiere_gallery_columns" id="lumiere_gallery_columns" class="widefat">
			<option value="2" <?php selected( $columns, '2' ); ?>>2</option>
			<option value="3" <?php selected( $columns, '3' ); ?>>3</option>
			<option value="4" <?php selected( $columns, '4' ); ?>>4</option>
			<option value="5" <?php selected( $columns, '5' ); ?>>5</option>
		</select>
	</p>
	
	<p>
		<label>
			<input type="checkbox" name="lumiere_gallery_lightbox" value="1" <?php checked( $lightbox, '1' ); ?> />
			<?php _e( 'Activer la lightbox', 'lumiere-portfolio' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Save gallery meta
 */
function lumiere_save_gallery_meta( $post_id ) {
	// Check nonce
	if ( ! isset( $_POST['lumiere_gallery_images_nonce'] ) || 
	     ! wp_verify_nonce( $_POST['lumiere_gallery_images_nonce'], 'lumiere_gallery_images' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save gallery images
	if ( isset( $_POST['lumiere_gallery_images'] ) ) {
		update_post_meta( $post_id, '_lumiere_gallery_images', sanitize_text_field( $_POST['lumiere_gallery_images'] ) );
	}

	// Save settings
	if ( isset( $_POST['lumiere_gallery_settings_nonce'] ) && 
	     wp_verify_nonce( $_POST['lumiere_gallery_settings_nonce'], 'lumiere_gallery_settings' ) ) {
		
		if ( isset( $_POST['lumiere_gallery_layout'] ) ) {
			update_post_meta( $post_id, '_lumiere_gallery_layout', sanitize_text_field( $_POST['lumiere_gallery_layout'] ) );
		}
		
		if ( isset( $_POST['lumiere_gallery_columns'] ) ) {
			update_post_meta( $post_id, '_lumiere_gallery_columns', sanitize_text_field( $_POST['lumiere_gallery_columns'] ) );
		}
		
		$lightbox = isset( $_POST['lumiere_gallery_lightbox'] ) ? '1' : '0';
		update_post_meta( $post_id, '_lumiere_gallery_lightbox', $lightbox );
	}
}
add_action( 'save_post_lumiere_gallery', 'lumiere_save_gallery_meta' );
