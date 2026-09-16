<?php
/**
 * Server-side wrapper for the React lead-form island.
 *
 * @var array $attributes Block attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow      = isset( $attributes['eyebrow'] ) ? wp_kses_post( $attributes['eyebrow'] ) : '';
$heading      = isset( $attributes['heading'] ) ? wp_kses_post( $attributes['heading'] ) : '';
$button_label = isset( $attributes['buttonLabel'] ) ? sanitize_text_field( $attributes['buttonLabel'] ) : __( 'Send request', 'studio-blocks' );
$rest_url     = rest_url( 'studio-blocks/v1/leads' );
$wrapper      = get_block_wrapper_attributes( array( 'class' => 'studio-lead-form-block' ) );
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="studio-lead-form-block__copy">
		<?php if ( $eyebrow ) : ?>
			<p class="studio-lead-form-block__eyebrow"><?php echo $eyebrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
		<?php if ( $heading ) : ?>
			<h2 class="studio-lead-form-block__heading"><?php echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<?php endif; ?>
		<p class="studio-lead-form-block__supporting-copy">
			<?php esc_html_e( 'The form is validated in React and again in PHP before the request is stored in WordPress.', 'studio-blocks' ); ?>
		</p>
	</div>

	<div
		class="studio-lead-form-root"
		data-rest-url="<?php echo esc_url( $rest_url ); ?>"
		data-button-label="<?php echo esc_attr( $button_label ); ?>"
		data-success-message="<?php echo esc_attr__( 'Thank you. Your request has been received.', 'studio-blocks' ); ?>"
	></div>
	<noscript><?php esc_html_e( 'JavaScript is required to use this form.', 'studio-blocks' ); ?></noscript>
</section>
