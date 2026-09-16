<?php
/**
 * Server-side renderer for the service grid.
 *
 * @var array $attributes Block attributes.
 * @var string $content Saved block content.
 * @var WP_Block $block Parsed block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = isset( $attributes['heading'] ) ? wp_kses_post( $attributes['heading'] ) : '';
$intro   = isset( $attributes['intro'] ) ? wp_kses_post( $attributes['intro'] ) : '';
$columns = isset( $attributes['columns'] ) ? max( 2, min( 4, absint( $attributes['columns'] ) ) ) : 3;
$limit   = isset( $attributes['limit'] ) ? max( 1, min( 12, absint( $attributes['limit'] ) ) ) : 6;

$query = new WP_Query(
	array(
		'post_type'      => 'studio_service',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
	)
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'studio-service-grid',
		'style' => '--studio-columns:' . $columns,
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="studio-service-grid__header">
		<?php if ( $heading ) : ?>
			<h2 class="studio-service-grid__heading"><?php echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<?php endif; ?>
		<?php if ( $intro ) : ?>
			<p class="studio-service-grid__intro"><?php echo $intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
	</div>

	<?php if ( $query->have_posts() ) : ?>
		<div class="studio-service-grid__items">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 26 );
				?>
				<article class="studio-service-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="studio-service-card__media">
							<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="studio-service-card__content">
						<span class="studio-service-card__eyebrow"><?php esc_html_e( 'Service', 'studio-blocks' ); ?></span>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( $excerpt ); ?></p>
						<a class="studio-service-card__link" href="<?php the_permalink(); ?>">
							<?php esc_html_e( 'View service', 'studio-blocks' ); ?>
							<span aria-hidden="true">→</span>
						</a>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p class="studio-service-grid__empty">
			<?php esc_html_e( 'Add published Studio Service posts to populate this block.', 'studio-blocks' ); ?>
		</p>
	<?php endif; ?>
</section>
<?php wp_reset_postdata(); ?>
