<?php
/**
 * PDP routine ladder presentation shell.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'resq_core_is_active' ) || ! resq_core_is_active() || ! function_exists( 'resq_get_product_routine_ladder' ) ) {
	return;
}

global $product;

if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$product_id     = (int) $product->get_id();
$ladder_payload = resq_get_product_routine_ladder( $product_id );

if ( empty( $ladder_payload ) || empty( $ladder_payload['steps'] ) ) {
	return;
}

$routine_title = ! empty( $ladder_payload['title'] )
	? $ladder_payload['title']
	: __( 'Complete Your Step-by-Step Regimen', 'resq-clean-pro' );
$bundle_target = ! empty( $ladder_payload['bundle_target'] ) ? absint( $ladder_payload['bundle_target'] ) : 0;
$bundle_label  = ! empty( $ladder_payload['bundle_label'] )
	? $ladder_payload['bundle_label']
	: __( 'Upgrade to Full Routine Kit', 'resq-clean-pro' );
?>

<section class="resq-routine-ladder" aria-labelledby="resq-routine-ladder-heading">
	<div class="resq-routine-ladder__header">
		<h3 id="resq-routine-ladder-heading" class="resq-routine-ladder__title">
			<?php echo esc_html( $routine_title ); ?>
		</h3>
		<?php if ( ! empty( $ladder_payload['description'] ) ) : ?>
			<p class="resq-routine-ladder__description">
				<?php echo esc_html( $ladder_payload['description'] ); ?>
			</p>
		<?php endif; ?>
	</div>

	<ol class="resq-routine-ladder__steps">
		<?php foreach ( $ladder_payload['steps'] as $step ) : ?>
			<?php
			$is_current  = ! empty( $step['is_current'] );
			$is_optional = ! empty( $step['is_optional'] );
			$in_stock    = isset( $step['in_stock'] ) ? (bool) $step['in_stock'] : true;
			$step_class  = 'resq-routine-step';

			if ( $is_current ) {
				$step_class .= ' is-current';
			}
			if ( ! $in_stock ) {
				$step_class .= ' is-out-of-stock';
			}
			?>
			<li class="<?php echo esc_attr( $step_class ); ?>">
				<div class="resq-routine-step__badge" aria-hidden="true">
					<?php echo esc_html( (string) ( $step['order'] ?? '' ) ); ?>
				</div>

				<div class="resq-routine-step__content">
					<span class="resq-routine-step__role">
						<?php echo esc_html( (string) ( $step['title'] ?? '' ) ); ?>
						<?php if ( $is_optional ) : ?>
							<span class="resq-routine-step__current-note">(<?php esc_html_e( 'optional', 'resq-clean-pro' ); ?>)</span>
						<?php endif; ?>
					</span>

					<?php if ( ! $in_stock ) : ?>
						<span class="resq-routine-step__stock"><?php esc_html_e( 'Out of Stock', 'resq-clean-pro' ); ?></span>
					<?php endif; ?>

					<p class="resq-routine-step__product">
						<?php if ( ! empty( $step['product_url'] ) && ! $is_current ) : ?>
							<a class="resq-routine-step__link" href="<?php echo esc_url( $step['product_url'] ); ?>">
								<?php echo esc_html( (string) ( $step['product_title'] ?? '' ) ); ?>
							</a>
						<?php else : ?>
							<span><?php echo esc_html( (string) ( $step['product_title'] ?? '' ) ); ?></span>
							<?php if ( $is_current ) : ?>
								<span class="resq-routine-step__current-note">&mdash; <?php esc_html_e( 'You are viewing this step', 'resq-clean-pro' ); ?></span>
							<?php endif; ?>
						<?php endif; ?>
					</p>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>

	<?php if ( $bundle_target > 0 ) : ?>
		<?php $bundle_url = get_permalink( $bundle_target ); ?>
		<?php if ( $bundle_url ) : ?>
			<div class="resq-routine-ladder__bundle">
				<div class="resq-routine-ladder__bundle-text">
					<strong><?php esc_html_e( 'Want maximum performance and savings?', 'resq-clean-pro' ); ?></strong>
					<span><?php esc_html_e( 'Get all steps combined into an outcome-oriented routine kit.', 'resq-clean-pro' ); ?></span>
				</div>
				<a class="resq-routine-ladder__bundle-btn" href="<?php echo esc_url( $bundle_url ); ?>">
					<?php echo esc_html( $bundle_label ); ?>
				</a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</section>
