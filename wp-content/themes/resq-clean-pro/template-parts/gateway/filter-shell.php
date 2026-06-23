<?php
/**
 * Gateway filter UI shell — structural only; taxonomy wiring deferred to Phase 8.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $context Resolved product context from plugin helper.
 */

defined( 'ABSPATH' ) || exit;

$context = isset( $context ) && is_array( $context ) ? $context : array();
$filters = isset( $context['filters'] ) && is_array( $context['filters'] ) ? $context['filters'] : array();

$filter_groups = array(
	array(
		'key'   => 'audience',
		'label' => __( 'Audience', 'resq-clean-pro' ),
		'items' => isset( $filters['audience'] ) && is_array( $filters['audience'] ) ? $filters['audience'] : array(),
	),
	array(
		'key'   => 'concern',
		'label' => __( 'Concern', 'resq-clean-pro' ),
		'items' => isset( $filters['concern'] ) && is_array( $filters['concern'] ) ? $filters['concern'] : array(),
	),
	array(
		'key'   => 'format',
		'label' => __( 'Format', 'resq-clean-pro' ),
		'items' => isset( $filters['format'] ) && is_array( $filters['format'] ) ? $filters['format'] : array(),
	),
);
?>

<section class="resq-gateway__section resq-filter-shell" aria-labelledby="resq-filter-shell-heading">
	<h2 id="resq-filter-shell-heading" class="resq-filter-shell__title">
		<?php esc_html_e( 'Refine Results', 'resq-clean-pro' ); ?>
	</h2>

	<form class="resq-filter-shell__form" method="get" action="">
		<div class="resq-filter-shell__groups">
			<?php foreach ( $filter_groups as $group ) : ?>
				<div class="resq-filter-shell__group">
					<span class="resq-filter-shell__label"><?php echo esc_html( $group['label'] ); ?></span>

					<?php if ( ! empty( $group['items'] ) ) : ?>
						<ul class="resq-filter-shell__options">
							<?php foreach ( $group['items'] as $item ) : ?>
								<li><?php echo esc_html( (string) $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<p class="resq-filter-shell__placeholder">
							<?php esc_html_e( 'Filter options will appear when product data is configured.', 'resq-clean-pro' ); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</form>
</section>
