<?php
/**
 * Gateway / shop filter shell — real taxonomy controls (Stream F).
 *
 * Renders audience, concern, and product-role checkbox groups populated from
 * live taxonomy terms. Submits via GET so URLs are bookmarkable.
 * Active state is read from current GET params via ResQ_Core_Product_Filters.
 *
 * @package ResQ_Clean_Pro
 *
 * @var array $context Resolved product context from plugin helper (optional).
 *                     When present, the context audience pre-seeds the hidden
 *                     audience value on gateway pages so filtering stays scoped.
 */

defined( 'ABSPATH' ) || exit;

$context      = isset( $context ) && is_array( $context ) ? $context : array();
$gateway_slug = (string) ( $context['context_id'] ?? '' );
$context_type = (string) ( $context['context_type'] ?? '' );

// Active filter values from current request.
$active = class_exists( 'ResQ_Core_Product_Filters' )
	? ResQ_Core_Product_Filters::get_active_filters()
	: array();

$has_active = ! empty( $active );

/**
 * Build filter groups. Each group has:
 *  - param    : GET param name
 *  - taxonomy : WP taxonomy slug
 *  - label    : Display heading
 *  - hidden   : Optional pre-set value injected as a hidden input on gateway
 *               pages (locks the audience scope without showing the control).
 *  - hide     : Whether to hide the visual control (still sent as hidden).
 */
$filter_groups = array(
	array(
		'param'    => 'resq_audience',
		'taxonomy' => 'resq_audience',
		'label'    => __( 'Audience', 'resq-clean-pro' ),
		'hidden'   => ( 'gateway' === $context_type && ! empty( $context['audience'] ) )
			? (string) $context['audience']
			: '',
		'hide'     => ( 'gateway' === $context_type && ! empty( $context['audience'] ) ),
	),
	array(
		'param'    => 'resq_concern',
		'taxonomy' => 'resq_concern',
		'label'    => __( 'Concern', 'resq-clean-pro' ),
		'hidden'   => '',
		'hide'     => false,
	),
	array(
		'param'    => 'resq_product_role',
		'taxonomy' => 'resq_product_role',
		'label'    => __( 'Product type', 'resq-clean-pro' ),
		'hidden'   => '',
		'hide'     => false,
	),
);

// Resolve current page URL for form action (strips existing resq_ params).
$current_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
if ( is_tax() || is_post_type_archive( 'product' ) ) {
	$current_url = get_pagenum_link( 1, false );
}

// Strip resq_ filter params from URL so form submission is clean.
$clean_url = remove_query_arg( array( 'resq_audience', 'resq_concern', 'resq_product_role', 'paged' ), $current_url );

// Check whether any group has renderable terms.
$has_any_terms = false;
foreach ( $filter_groups as $group ) {
	if ( $group['hide'] ) {
		continue;
	}
	if ( ! taxonomy_exists( $group['taxonomy'] ) ) {
		continue;
	}
	$terms = get_terms( array( 'taxonomy' => $group['taxonomy'], 'hide_empty' => true, 'number' => 100 ) );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		$has_any_terms = true;
		break;
	}
}

if ( ! $has_any_terms ) {
	return;
}
?>

<section class="resq-gateway__section resq-filter-shell" aria-labelledby="resq-filter-shell-heading">
	<div class="resq-filter-shell__header">
		<h2 id="resq-filter-shell-heading" class="resq-filter-shell__title">
			<?php esc_html_e( 'Refine Results', 'resq-clean-pro' ); ?>
		</h2>

		<?php if ( $has_active ) : ?>
			<a href="<?php echo esc_url( $clean_url ); ?>" class="resq-filter-shell__clear">
				<?php esc_html_e( 'Clear filters', 'resq-clean-pro' ); ?>
			</a>
		<?php endif; ?>
	</div>

	<form class="resq-filter-shell__form" method="get" action="<?php echo esc_url( $clean_url ); ?>">

		<?php foreach ( $filter_groups as $group ) : ?>
			<?php
			$param    = $group['param'];
			$taxonomy = $group['taxonomy'];

			// Gateway pages: inject the scoped audience as a hidden input.
			if ( ! empty( $group['hidden'] ) ) :
			?>
				<input type="hidden" name="<?php echo esc_attr( $param ); ?>" value="<?php echo esc_attr( $group['hidden'] ); ?>">
				<?php continue; ?>
			<?php endif; ?>

			<?php
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true, 'orderby' => 'name', 'number' => 100 ) );
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				continue;
			}

			$active_slugs = $active[ $param ] ?? array();
			?>

			<fieldset class="resq-filter-shell__group">
				<legend class="resq-filter-shell__label"><?php echo esc_html( $group['label'] ); ?></legend>

				<ul class="resq-filter-shell__options">
					<?php foreach ( $terms as $term ) : ?>
						<?php
						$slug      = $term->slug;
						$name      = $term->name;
						$input_id  = 'resq-filter-' . esc_attr( $param ) . '-' . esc_attr( $slug );
						$is_active = in_array( $slug, $active_slugs, true );
						?>
						<li class="resq-filter-shell__option">
							<label for="<?php echo $input_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>" class="resq-filter-shell__option-label<?php echo $is_active ? ' is-active' : ''; ?>">
								<input
									type="checkbox"
									id="<?php echo $input_id; // phpcs:ignore ?>"
									name="<?php echo esc_attr( $param ); ?>[]"
									value="<?php echo esc_attr( $slug ); ?>"
									<?php checked( $is_active ); ?>
									class="resq-filter-shell__checkbox"
								>
								<?php echo esc_html( $name ); ?>
								<?php if ( $term->count > 0 ) : ?>
									<span class="resq-filter-shell__count">(<?php echo (int) $term->count; ?>)</span>
								<?php endif; ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			</fieldset>

		<?php endforeach; ?>

		<button type="submit" class="resq-filter-shell__submit btn btn--secondary">
			<?php esc_html_e( 'Apply filters', 'resq-clean-pro' ); ?>
		</button>

	</form>
</section>
