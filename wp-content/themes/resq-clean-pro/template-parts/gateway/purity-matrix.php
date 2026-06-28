<?php
/**
 * CBD gateway purity & isolation matrix — 3-column technical breakdown.
 *
 * DRAFT, review-required. Neutral, transparency-focused copy only — no medical,
 * therapeutic, or outcome claims (docs/05 CBD Isolation + Medical-Claim
 * Avoidance). Renders only on the isolated CBD surface, behind the compliance
 * notice + age gate.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $purity Purity column definitions from the landing content map.
 */

defined( 'ABSPATH' ) || exit;

$purity = isset( $purity ) && is_array( $purity ) ? $purity : array();

if ( empty( $purity ) ) {
	return;
}
?>

<section class="resq-purity-matrix" aria-labelledby="resq-purity-matrix-heading">
	<h2 id="resq-purity-matrix-heading" class="resq-purity-matrix__heading">
		<?php esc_html_e( 'Purity & isolation', 'resq-clean-pro' ); ?>
	</h2>

	<ul class="resq-purity-matrix__grid">
		<?php foreach ( $purity as $column ) : ?>
			<?php
			if ( ! is_array( $column ) ) {
				continue;
			}
			$col_title = (string) ( $column['title'] ?? '' );
			$col_body  = (string) ( $column['body'] ?? '' );

			if ( '' === $col_title ) {
				continue;
			}
			?>
			<li class="resq-purity-matrix__col">
				<h3 class="resq-purity-matrix__col-title"><?php echo esc_html( $col_title ); ?></h3>
				<?php if ( '' !== $col_body ) : ?>
					<p class="resq-purity-matrix__col-body"><?php echo esc_html( $col_body ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
