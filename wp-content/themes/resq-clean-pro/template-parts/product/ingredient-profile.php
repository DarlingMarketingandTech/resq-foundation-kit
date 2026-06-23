<?php
/**
 * PDP ingredient profile shell.
 *
 * Renders key ingredients from resq_get_product_ingredient_profile(). Only
 * claim_safe descriptors are displayed — claim_safe:false items show the
 * label without a descriptor to avoid unsupported claims in theme copy.
 * Returns silently when plugin is inactive or no profile data exists.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

if (
	! function_exists( 'resq_core_is_active' )
	|| ! resq_core_is_active()
	|| ! function_exists( 'resq_get_product_ingredient_profile' )
) {
	return;
}

global $product;

if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$ingredients = resq_get_product_ingredient_profile( (int) $product->get_id() );

if ( empty( $ingredients ) || ! is_array( $ingredients ) ) {
	return;
}
?>
<section class="resq-ingredient-profile" aria-labelledby="resq-ingredient-profile-heading">

	<h3 id="resq-ingredient-profile-heading" class="resq-ingredient-profile__title">
		<?php esc_html_e( 'Key Ingredients', 'resq-clean-pro' ); ?>
	</h3>

	<ul class="resq-ingredient-profile__list">
		<?php foreach ( $ingredients as $ingredient ) : ?>
			<?php
			if ( empty( $ingredient['label'] ) ) {
				continue;
			}
			$claim_safe = ! empty( $ingredient['claim_safe'] );
			$item_class = 'resq-ingredient-profile__item';
			if ( ! $claim_safe ) {
				$item_class .= ' resq-ingredient-profile__item--unclaimed';
			}
			?>
			<li class="<?php echo esc_attr( $item_class ); ?>">
				<strong class="resq-ingredient-profile__label">
					<?php echo esc_html( (string) $ingredient['label'] ); ?>
				</strong>
				<?php if ( $claim_safe && ! empty( $ingredient['descriptor'] ) ) : ?>
					<p class="resq-ingredient-profile__descriptor">
						<?php echo esc_html( (string) $ingredient['descriptor'] ); ?>
					</p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>

</section>
