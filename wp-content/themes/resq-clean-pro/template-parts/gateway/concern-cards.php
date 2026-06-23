<?php
/**
 * Gateway concern card grid — plugin context concerns only.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $concerns Concern objects from resq_resolve_product_context().
 */

defined( 'ABSPATH' ) || exit;

$concerns = isset( $concerns ) && is_array( $concerns ) ? $concerns : array();

if ( empty( $concerns ) ) {
	return;
}

$cards = array();

foreach ( $concerns as $concern ) {
	if ( is_string( $concern ) && '' !== $concern ) {
		$cards[] = array(
			'slug'        => sanitize_title( $concern ),
			'label'       => $concern,
			'description' => '',
			'url'         => '',
		);
		continue;
	}

	if ( ! is_array( $concern ) ) {
		continue;
	}

	$slug  = sanitize_title( (string) ( $concern['slug'] ?? '' ) );
	$label = (string) ( $concern['label'] ?? $concern['name'] ?? '' );

	if ( '' === $slug && '' === $label ) {
		continue;
	}

	$url = (string) ( $concern['url'] ?? '' );

	$cards[] = array(
		'slug'        => $slug,
		'label'       => $label,
		'description' => (string) ( $concern['description'] ?? '' ),
		'url'         => $url,
	);
}

if ( empty( $cards ) ) {
	return;
}
?>

<section class="resq-gateway__section" aria-labelledby="resq-concern-grid-heading">
	<h2 id="resq-concern-grid-heading" class="resq-gateway__section-title">
		<?php esc_html_e( 'Shop by Concern', 'resq-clean-pro' ); ?>
	</h2>

	<ul class="resq-concern-grid">
		<?php foreach ( $cards as $card ) : ?>
			<li class="resq-concern-card">
				<?php if ( ! empty( $card['url'] ) ) : ?>
					<a class="resq-concern-card__link" href="<?php echo esc_url( $card['url'] ); ?>">
						<?php echo esc_html( $card['label'] ); ?>
					</a>
				<?php else : ?>
					<span class="resq-concern-card__link"><?php echo esc_html( $card['label'] ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $card['description'] ) ) : ?>
					<p class="resq-concern-card__description"><?php echo esc_html( $card['description'] ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
