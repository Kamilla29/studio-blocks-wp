<?php
/**
 * Plugin bootstrap and WordPress registrations.
 *
 * @package StudioBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Studio_Blocks_Plugin {
	/**
	 * Register plugin hooks.
	 */
	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'load_textdomain' ), 1 );
		add_action( 'init', array( __CLASS__, 'register_content_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_blocks' ), 20 );
		add_action( 'rest_api_init', array( 'Studio_Blocks_REST_Controller', 'register_routes' ) );
	}

	/**
	 * Load translations.
	 */
	public static function load_textdomain(): void {
		load_plugin_textdomain(
			'studio-blocks',
			false,
			dirname( plugin_basename( STUDIO_BLOCKS_DIR . 'studio-blocks.php' ) ) . '/languages'
		);
	}

	/**
	 * Register the demo service and lead content types.
	 */
	public static function register_content_types(): void {
		register_post_type(
			'studio_service',
			array(
				'labels' => array(
					'name'          => __( 'Studio Services', 'studio-blocks' ),
					'singular_name' => __( 'Studio Service', 'studio-blocks' ),
					'add_new_item'  => __( 'Add Studio Service', 'studio-blocks' ),
					'edit_item'     => __( 'Edit Studio Service', 'studio-blocks' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-art',
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
				'rewrite'      => array( 'slug' => 'services' ),
			)
		);

		register_post_type(
			'studio_lead',
			array(
				'labels' => array(
					'name'          => __( 'Studio Leads', 'studio-blocks' ),
					'singular_name' => __( 'Studio Lead', 'studio-blocks' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_rest'        => false,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-email-alt',
				'supports'            => array( 'title', 'editor', 'custom-fields' ),
				'capabilities'        => array(
					'create_posts' => 'do_not_allow',
				),
				'map_meta_cap'        => true,
			)
		);
	}

	/**
	 * Register built blocks when compiled assets are present.
	 */
	public static function register_blocks(): void {
		$blocks = array( 'service-grid', 'lead-form' );

		foreach ( $blocks as $block ) {
			$path = STUDIO_BLOCKS_DIR . 'build/blocks/' . $block;

			if ( file_exists( $path . '/block.json' ) ) {
				register_block_type( $path );
			}
		}
	}
}
