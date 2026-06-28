<?php
/**
 * Pet gateway rescue mission band.
 *
 * Compliance-adapted mission copy (docs/05 Donation caution): mission-aligned,
 * "portion of purchases" language — no buy-1-give-1 / identical-donation claim
 * until fulfillment + accounting can prove the mechanism.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array  $mission  Mission definition from the landing content map.
 * @var string $base_url Gateway base URL.
 * @var string $cta_url  Optional pre-resolved CTA URL (e.g. Learn hub).
 */

defined( 'ABSPATH' ) || exit;

$mission = isset( $mission ) && is_array( $mission ) ? $mission : array();

if ( empty( $mission ) ) {
	return;
}

$badge     = (string) ( $mission['badge'] ?? '' );
$headline  = (string) ( $mission['headline'] ?? '' );
$body      = (string) ( $mission['body'] ?? '' );
$cta_label = (string) ( $mission['cta_label'] ?? '' );
$cta_url   = isset( $cta_url ) ? (string) $cta_url : '';

if ( '' === $headline && '' === $body ) {
	return;
}
?>

<section class="resq-mission-band" aria-labelledby="resq-mission-band-title">
	<div class="resq-mission-band__inner">
		<?php if ( '' !== $badge ) : ?>
			<span class="resq-mission-band__badge"><?php echo esc_html( $badge ); ?></span>
		<?php endif; ?>

		<?php if ( '' !== $headline ) : ?>
			<h2 id="resq-mission-band-title" class="resq-mission-band__headline"><?php echo esc_html( $headline ); ?></h2>
		<?php endif; ?>

		<?php if ( '' !== $body ) : ?>
			<p class="resq-mission-band__body"><?php echo esc_html( $body ); ?></p>
		<?php endif; ?>

		<?php if ( '' !== $cta_label && '' !== $cta_url ) : ?>
			<a class="resq-mission-band__cta" href="<?php echo esc_url( $cta_url ); ?>">
				<?php echo esc_html( $cta_label ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>
