<?php
/**
 * Public REST endpoint used by the React lead-form block.
 *
 * @package StudioBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Studio_Blocks_REST_Controller {
	private const NAMESPACE = 'studio-blocks/v1';
	private const ROUTE     = '/leads';

	/**
	 * Register routes.
	 */
	public static function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			self::ROUTE,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'create_lead' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Store a validated lead as a private WordPress post.
	 *
	 * @param WP_REST_Request $request REST request.
	 * @return WP_REST_Response|WP_Error
	 */
	public static function create_lead( WP_REST_Request $request ) {
		if ( self::is_rate_limited() ) {
			return new WP_Error(
				'studio_blocks_rate_limited',
				__( 'Too many requests. Please try again in a few minutes.', 'studio-blocks' ),
				array( 'status' => 429 )
			);
		}

		$payload = $request->get_json_params();
		$payload = is_array( $payload ) ? $payload : array();

		// Honeypot: accept the request without storing anything to avoid helping bots iterate.
		if ( ! empty( $payload['website'] ) ) {
			return new WP_REST_Response( array( 'success' => true ), 201 );
		}

		$name    = sanitize_text_field( $payload['name'] ?? '' );
		$email   = sanitize_email( $payload['email'] ?? '' );
		$company = sanitize_text_field( $payload['company'] ?? '' );
		$message = sanitize_textarea_field( $payload['message'] ?? '' );

		$errors = array();

		if ( mb_strlen( $name ) < 2 ) {
			$errors['name'] = __( 'Please enter your name.', 'studio-blocks' );
		}

		if ( ! is_email( $email ) ) {
			$errors['email'] = __( 'Please enter a valid email address.', 'studio-blocks' );
		}

		if ( mb_strlen( $message ) < 20 || mb_strlen( $message ) > 1000 ) {
			$errors['message'] = __( 'Message must contain between 20 and 1000 characters.', 'studio-blocks' );
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error(
				'studio_blocks_validation_failed',
				__( 'Please correct the highlighted fields.', 'studio-blocks' ),
				array(
					'status' => 422,
					'fields' => $errors,
				)
			);
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'studio_lead',
				'post_status'  => 'private',
				'post_title'   => sprintf( '%s — %s', $name, $email ),
				'post_content' => $message,
				'meta_input'   => array(
					'_studio_email'   => $email,
					'_studio_company' => $company,
					'_studio_source'  => 'lead-form-block',
				),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return new WP_Error(
				'studio_blocks_store_failed',
				__( 'The request could not be saved. Please try again.', 'studio-blocks' ),
				array( 'status' => 500 )
			);
		}

		self::mark_rate_limit();

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Thank you. Your request has been received.', 'studio-blocks' ),
			),
			201
		);
	}

	/**
	 * Check a short anonymous rate-limit window.
	 */
	private static function is_rate_limited(): bool {
		return (bool) get_transient( self::rate_limit_key() );
	}

	/**
	 * Mark the current address for five minutes after a successful submission.
	 */
	private static function mark_rate_limit(): void {
		set_transient( self::rate_limit_key(), 1, 5 * MINUTE_IN_SECONDS );
	}

	/**
	 * Generate a non-reversible transient key from the remote address.
	 */
	private static function rate_limit_key(): string {
		$address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
		$hash    = hash_hmac( 'sha256', $address, wp_salt( 'nonce' ) );

		return 'studio_lead_' . substr( $hash, 0, 32 );
	}
}
