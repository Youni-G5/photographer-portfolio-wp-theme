<?php
/**
 * Custom Taxonomies Registration
 *
 * @package Lumière_Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register taxonomies for galleries
 */
function lumiere_register_taxonomies() {
	// Genres (Portrait, Mariage, Street, Paysage, etc.)
	$genre_labels = array(
		'name'                       => __( 'Genres', 'lumiere-portfolio' ),
		'singular_name'              => __( 'Genre', 'lumiere-portfolio' ),
		'search_items'               => __( 'Rechercher des genres', 'lumiere-portfolio' ),
		'popular_items'              => __( 'Genres populaires', 'lumiere-portfolio' ),
		'all_items'                  => __( 'Tous les genres', 'lumiere-portfolio' ),
		'edit_item'                  => __( 'Modifier le genre', 'lumiere-portfolio' ),
		'update_item'                => __( 'Mettre à jour le genre', 'lumiere-portfolio' ),
		'add_new_item'               => __( 'Ajouter un nouveau genre', 'lumiere-portfolio' ),
		'new_item_name'              => __( 'Nouveau nom de genre', 'lumiere-portfolio' ),
		'separate_items_with_commas' => __( 'Séparer les genres avec des virgules', 'lumiere-portfolio' ),
		'add_or_remove_items'        => __( 'Ajouter ou retirer des genres', 'lumiere-portfolio' ),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisés', 'lumiere-portfolio' ),
		'menu_name'                  => __( 'Genres', 'lumiere-portfolio' ),
	);

	register_taxonomy( 'lumiere_genre', 'lumiere_gallery', array(
		'hierarchical'      => true,
		'labels'            => $genre_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 
			'slug'         => 'genre',
			'with_front'   => false,
			'hierarchical' => true,
		),
		'show_in_quick_edit' => true,
	) );

	// Sessions (Studio, Extérieur, Nuit, Golden Hour, etc.)
	$session_labels = array(
		'name'                       => __( 'Sessions', 'lumiere-portfolio' ),
		'singular_name'              => __( 'Session', 'lumiere-portfolio' ),
		'search_items'               => __( 'Rechercher des sessions', 'lumiere-portfolio' ),
		'popular_items'              => __( 'Sessions populaires', 'lumiere-portfolio' ),
		'all_items'                  => __( 'Toutes les sessions', 'lumiere-portfolio' ),
		'edit_item'                  => __( 'Modifier la session', 'lumiere-portfolio' ),
		'update_item'                => __( 'Mettre à jour la session', 'lumiere-portfolio' ),
		'add_new_item'               => __( 'Ajouter une nouvelle session', 'lumiere-portfolio' ),
		'new_item_name'              => __( 'Nouveau nom de session', 'lumiere-portfolio' ),
		'separate_items_with_commas' => __( 'Séparer les sessions avec des virgules', 'lumiere-portfolio' ),
		'add_or_remove_items'        => __( 'Ajouter ou retirer des sessions', 'lumiere-portfolio' ),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisées', 'lumiere-portfolio' ),
		'menu_name'                  => __( 'Sessions', 'lumiere-portfolio' ),
	);

	register_taxonomy( 'lumiere_session', 'lumiere_gallery', array(
		'hierarchical'       => false,
		'labels'             => $session_labels,
		'show_ui'            => true,
		'show_admin_column'  => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'session' ),
		'show_in_quick_edit' => true,
	) );

	// Lieux (Paris, Londres, Nature, Urbain, etc.)
	$location_labels = array(
		'name'                       => __( 'Lieux', 'lumiere-portfolio' ),
		'singular_name'              => __( 'Lieu', 'lumiere-portfolio' ),
		'search_items'               => __( 'Rechercher des lieux', 'lumiere-portfolio' ),
		'popular_items'              => __( 'Lieux populaires', 'lumiere-portfolio' ),
		'all_items'                  => __( 'Tous les lieux', 'lumiere-portfolio' ),
		'edit_item'                  => __( 'Modifier le lieu', 'lumiere-portfolio' ),
		'update_item'                => __( 'Mettre à jour le lieu', 'lumiere-portfolio' ),
		'add_new_item'               => __( 'Ajouter un nouveau lieu', 'lumiere-portfolio' ),
		'new_item_name'              => __( 'Nouveau nom de lieu', 'lumiere-portfolio' ),
		'menu_name'                  => __( 'Lieux', 'lumiere-portfolio' ),
	);

	register_taxonomy( 'lumiere_location', 'lumiere_gallery', array(
		'hierarchical'       => true,
		'labels'             => $location_labels,
		'show_ui'            => true,
		'show_admin_column'  => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'lieu' ),
		'show_in_quick_edit' => true,
	) );
}
add_action( 'init', 'lumiere_register_taxonomies' );

/**
 * Add default terms on theme activation
 */
function lumiere_add_default_terms() {
	if ( get_option( 'lumiere_default_terms_added' ) ) {
		return;
	}

	// Default genres
	$default_genres = array(
		'Portrait',
		'Mariage',
		'Street Photography',
		'Paysage',
		'Architecture',
		'Mode',
		'Corporate',
		'Evénementiel',
		'Produit',
		'Noir & Blanc',
	);

	foreach ( $default_genres as $genre ) {
		if ( ! term_exists( $genre, 'lumiere_genre' ) ) {
			wp_insert_term( $genre, 'lumiere_genre' );
		}
	}

	// Default sessions
	$default_sessions = array(
		'Studio',
		'Extérieur',
		'Golden Hour',
		'Blue Hour',
		'Nuit',
		'Journée',
	);

	foreach ( $default_sessions as $session ) {
		if ( ! term_exists( $session, 'lumiere_session' ) ) {
			wp_insert_term( $session, 'lumiere_session' );
		}
	}

	// Default locations
	$default_locations = array(
		'Urbain',
		'Nature',
		'Plage',
		'Montagne',
		'Ville',
	);

	foreach ( $default_locations as $location ) {
		if ( ! term_exists( $location, 'lumiere_location' ) ) {
			wp_insert_term( $location, 'lumiere_location' );
		}
	}

	update_option( 'lumiere_default_terms_added', true );
}
add_action( 'after_switch_theme', 'lumiere_add_default_terms' );

/**
 * Add custom fields to taxonomy terms
 */
function lumiere_add_taxonomy_meta_fields( $term ) {
	$term_id = $term->term_id;
	$term_meta_icon = get_term_meta( $term_id, 'lumiere_tax_icon', true );
	$term_meta_color = get_term_meta( $term_id, 'lumiere_tax_color', true );
	?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="lumiere_tax_icon"><?php _e( 'Icône', 'lumiere-portfolio' ); ?></label>
		</th>
		<td>
			<input type="text" name="lumiere_tax_icon" id="lumiere_tax_icon" value="<?php echo esc_attr( $term_meta_icon ); ?>" />
			<p class="description"><?php _e( 'Classe CSS pour l\'icône (ex: fas fa-camera)', 'lumiere-portfolio' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="lumiere_tax_color"><?php _e( 'Couleur', 'lumiere-portfolio' ); ?></label>
		</th>
		<td>
			<input type="color" name="lumiere_tax_color" id="lumiere_tax_color" value="<?php echo esc_attr( $term_meta_color ? $term_meta_color : '#000000' ); ?>" />
			<p class="description"><?php _e( 'Couleur associée à ce terme', 'lumiere-portfolio' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'lumiere_genre_edit_form_fields', 'lumiere_add_taxonomy_meta_fields' );
add_action( 'lumiere_session_edit_form_fields', 'lumiere_add_taxonomy_meta_fields' );
add_action( 'lumiere_location_edit_form_fields', 'lumiere_add_taxonomy_meta_fields' );

/**
 * Save taxonomy custom fields
 */
function lumiere_save_taxonomy_meta_fields( $term_id ) {
	if ( isset( $_POST['lumiere_tax_icon'] ) ) {
		update_term_meta( $term_id, 'lumiere_tax_icon', sanitize_text_field( $_POST['lumiere_tax_icon'] ) );
	}
	if ( isset( $_POST['lumiere_tax_color'] ) ) {
		update_term_meta( $term_id, 'lumiere_tax_color', sanitize_hex_color( $_POST['lumiere_tax_color'] ) );
	}
}
add_action( 'edited_lumiere_genre', 'lumiere_save_taxonomy_meta_fields' );
add_action( 'edited_lumiere_session', 'lumiere_save_taxonomy_meta_fields' );
add_action( 'edited_lumiere_location', 'lumiere_save_taxonomy_meta_fields' );
