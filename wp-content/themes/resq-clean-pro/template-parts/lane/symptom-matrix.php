<?php
/**
 * Lane symptom benefit matrix — 3-column sensory outcomes.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $content Lane copy map entry.
 */

defined( 'ABSPATH' ) || exit;

$content = isset( $content ) && is_array( $content ) ? $content : array();
$matrix  = $content['symptom_matrix'] ?? array();

if ( ! is_array( $matrix ) || empty( $matrix ) ) {
	return;
}
?>

<section class="resq-lane-matrix" aria-labelledby="resq-lane-matrix-title">
	<h2 id="resq-lane-matrix-title" class="resq-lane-matrix__title">
		<?php esc_html_e( 'How this routine supports comfort', 'resq-clean-pro' ); ?>
	</h2>

	<ul class="resq-lane-matrix__grid">
		<?php foreach ( $matrix as $item ) : ?>
			<?php
			if ( ! is_array( $item ) ) {
				continue;
			}
			$title = (string) ( $item['title'] ?? '' );
			$body  = (string) ( $item['body'] ?? '' );
			if ( '' === $title ) {
				continue;
			}
			?>
			<li class="resq-lane-matrix__item">
				<h3 class="resq-lane-matrix__item-title"><?php echo esc_html( $title ); ?></h3>
				<?php if ( '' !== $body ) : ?>
					<p class="resq-lane-matrix__item-body"><?php echo esc_html( $body ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
