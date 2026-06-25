<?php
/**
 * Age verification gate modal (Phase 10 A4).
 *
 * Cookie-based soft gate shown on CBD surfaces until the visitor confirms age.
 * Neutral chrome copy only. Stronger third-party verification (if a payment
 * processor requires it) is a future swap behind the same `age_gate` flag.
 *
 * @package ResQ_Clean_Pro
 *
 * @var int $min_age Minimum age to confirm.
 */

defined( 'ABSPATH' ) || exit;

$min_age = isset( $min_age ) ? (int) $min_age : 21;
?>
<div
	id="resq-age-gate"
	class="resq-age-gate"
	role="dialog"
	aria-modal="true"
	aria-labelledby="resq-age-gate-title"
	aria-describedby="resq-age-gate-desc"
>
	<div class="resq-age-gate__overlay"></div>
	<div class="resq-age-gate__panel">
		<h2 id="resq-age-gate-title" class="resq-age-gate__title">
			<?php esc_html_e( 'Age verification', 'resq-clean-pro' ); ?>
		</h2>
		<p id="resq-age-gate-desc" class="resq-age-gate__desc">
			<?php
			printf(
				/* translators: %d: minimum age */
				esc_html__( 'You must be %d years or older to view this section. Please confirm your age to continue.', 'resq-clean-pro' ),
				absint( $min_age )
			);
			?>
		</p>
		<div class="resq-age-gate__actions">
			<button type="button" class="resq-age-gate__confirm btn btn--primary" data-resq-age-confirm>
				<?php
				printf(
					/* translators: %d: minimum age */
					esc_html__( 'I am %d or older', 'resq-clean-pro' ),
					absint( $min_age )
				);
				?>
			</button>
			<button type="button" class="resq-age-gate__decline btn btn--secondary" data-resq-age-decline>
				<?php esc_html_e( 'Take me back', 'resq-clean-pro' ); ?>
			</button>
		</div>
	</div>
</div>
