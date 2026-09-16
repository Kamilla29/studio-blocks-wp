<?php
/**
 * Plugin Name:       Studio Blocks
 * Description:       Gutenberg blocks demonstrating WordPress, React, TypeScript, PHP and REST integration.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Kamilla Kuanysheva
 * Text Domain:       studio-blocks
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STUDIO_BLOCKS_VERSION', '1.0.0' );
define( 'STUDIO_BLOCKS_DIR', plugin_dir_path( __FILE__ ) );
define( 'STUDIO_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

require_once STUDIO_BLOCKS_DIR . 'includes/class-studio-blocks-plugin.php';
require_once STUDIO_BLOCKS_DIR . 'includes/class-studio-blocks-rest-controller.php';

Studio_Blocks_Plugin::init();
