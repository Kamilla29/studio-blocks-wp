<?php
/**
 * Seed demo Studio Service posts.
 *
 * Usage from the WordPress root:
 * wp eval-file wp-content/plugins/studio-blocks/scripts/seed-services.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = array(
	array(
		'title'   => 'Brand websites',
		'excerpt' => 'Responsive commercial websites built from structured, reusable content.',
		'content' => 'A service entry used by the Studio Service Grid Gutenberg block.',
	),
	array(
		'title'   => 'Campaign landing pages',
		'excerpt' => 'Focused landing pages with accessible interactions and clear conversion paths.',
		'content' => 'A service entry used by the Studio Service Grid Gutenberg block.',
	),
	array(
		'title'   => 'Content systems',
		'excerpt' => 'WordPress content structures designed for safe editor workflows and scalable reuse.',
		'content' => 'A service entry used by the Studio Service Grid Gutenberg block.',
	),
);

foreach ( $services as $index => $service ) {
	$existing = get_page_by_title( $service['title'], OBJECT, 'studio_service' );
	if ( $existing ) {
		continue;
	}

	wp_insert_post(
		array(
			'post_type'    => 'studio_service',
			'post_status'  => 'publish',
			'post_title'   => $service['title'],
			'post_excerpt' => $service['excerpt'],
			'post_content' => $service['content'],
			'menu_order'   => $index,
		)
	);
}
