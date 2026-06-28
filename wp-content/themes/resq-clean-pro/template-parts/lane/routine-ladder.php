<?php
/**
 * Lane routine ladder — plugin-driven steps with canonical product CTA.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $lane Enriched lane data.
 */

defined( 'ABSPATH' ) || exit;

$lane = isset( $lane ) && is_array( $lane ) ? $lane : array();

$canonical_id = (int) ( $lane['canonical_id'] ?? 0 );
if ( $canonical_id <= 0 || ! function_exists( 'resq_get_product_routine_ladder' ) ) {
	return;
}

$ladder = resq_get_product_routine_ladder( $canonical_id );
$steps  = $ladder['steps'] ?? array();

if ( empty( $steps ) || ! is_array( $steps ) ) {
	return;
}

$bundle_target = (int) ( $ladder['bundle_target'] ?? 0 );
$bundle_savings = (string) ( $ladder['bundle_savings'] ?? '' );
$bundle_url     = $bundle_target > 0 && function_exists( 'get_permalink' ) ? get_permalink( $bundle_target ) : '';
?>

<section class="resq-lane-ladder" aria-labelledby="resq-lane-ladder-title">
	<h2 id="resq-lane-ladder-title" class="resq-lane-ladder__title">
		<?php esc_html_e( 'Complete your routine', 'resq-clean-pro' ); ?>
	</h2>

	<ol class="resq-lane-ladder__steps">
		<?php foreach ( $steps as $index => $step ) : ?>
			<?php
			if ( ! is_array( $step ) ) {
				continue;
			}
			$is_current = ! empty( $step['is_current'] );
			$title      = (string) ( $step['title'] ?? $step['product_title'] ?? '' );
			$url        = (string) ( $step['product_url'] ?? '' );
			$optional   = ! empty( $step['is_optional'] );
			?>
			<li class="resq-lane-ladder__step<?php echo $is_current ? ' is-current' : ''; ?>">
				<span class="resq-lane-ladder__step-num" aria-hidden="true"><?php echo (int) ( $index + 1 ); ?></span>
				<div class="resq-lane-ladder__step-body">
					<?php if ( '' !== $url && ! $is_current ) : ?>
						<a class="resq-lane-ladder__step-link" href="<?php echo esc_url( $url ); ?>">
							<?php echo esc_html( $title ); ?>
						</a>
					<?php else : ?>
						<span class="resq-lane-ladder__step-label">
							<?php echo esc_html( $title ); ?>
							<?php if ( $is_current ) : ?>
								<span class="resq-lane-ladder__step-badge"><?php esc_html_e( 'Viewing now', 'resq-clean-pro' ); ?></span>
							<?php endif; ?>
						</span>
					<?php endif; ?>
					<?php if ( $optional ) : ?>
						<span class="resq-lane-ladder__step-optional"><?php esc_html_e( 'Optional', 'resq-clean-pro' ); ?></span>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>

	<?php if ( $bundle_target > 0 && '' !== $bundle_url ) : ?>
		<div class="resq-lane-ladder__upgrade">
			<a class="resq-lane-ladder__upgrade-link btn btn--secondary" href="<?php echo esc_url( $bundle_url ); ?>">
				<?php esc_html_e( 'Upgrade to full routine kit', 'resq-clean-pro' ); ?>
			</a>
			<?php if ( '' !== $bundle_savings ) : ?>
				<span class="resq-lane-ladder__upgrade-savings"><?php echo wp_kses_post( $bundle_savings ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
