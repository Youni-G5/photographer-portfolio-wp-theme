<?php
/**
 * Handle contact form submission
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lumiere_handle_contact_form() {
	if ( ! isset( $_POST['lumiere_contact_nonce'] ) || ! wp_verify_nonce( $_POST['lumiere_contact_nonce'], 'lumiere_contact_form' ) ) {
		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? wp_kses_post( wp_unslash( $_POST['message'] ) ) : '';

	$admin_email = get_option( 'admin_email' );

	$subject = sprintf( __( 'Nouveau message depuis le site %s', 'lumiere-portfolio' ), get_bloginfo( 'name' ) );

	$body  = sprintf( __( 'Nom: %s', 'lumiere-portfolio' ), $name ) . "\n";
	$body .= sprintf( __( 'Email: %s', 'lumiere-portfolio' ), $email ) . "\n\n";
	$body .= __( 'Message:', 'lumiere-portfolio' ) . "\n" . $message . "\n";

	$headers = array();
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $email;
	}

	if ( $admin_email ) {
		wp_mail( $admin_email, $subject, $body, $headers );
	}

	// Redirect back with a query arg.
	$redirect = add_query_arg( 'contact', 'success', wp_get_referer() );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_nopriv_lumiere_contact_form', 'lumiere_handle_contact_form' );
add_action( 'admin_post_lumiere_contact_form', 'lumiere_handle_contact_form' );
