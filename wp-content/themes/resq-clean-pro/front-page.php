<?php
/**
 * Front page — unified ResQ Organics homepage.
 *
 * "One Cabinet, Not Two." Masterbrand entry bridging human skincare and
 * pet care. Section parts live in template-parts/home/.
 *
 * Compliance isolation (docs/05): this page never displays or cross-sells
 * CBD items, and uses supportive comfort language only.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$human_base = function_exists( 'resq_theme_get_gateway_page_url' )
	? trailingslashit( resq_theme_get_gateway_page_url( 'page-gateway-human.php', '/shop/human/' ) )
	: home_url( '/shop/human/' );

$pet_base = function_exists( 'resq_theme_get_gateway_page_url' )
	? trailingslashit( resq_theme_get_gateway_page_url( 'page-gateway-pet.php', '/shop/pet/' ) )
	: home_url( '/shop/pet/' );

$learn_url = function_exists( 'resq_theme_get_gateway_page_url' )
	? resq_theme_get_gateway_page_url( 'page-learn-index.php', '/learn/' )
	: home_url( '/learn/' );

get_header();
?>

<main id="primary-content" class="site-main resq-home" role="main">

	<?php
	get_template_part(
		'template-parts/home/hero',
		null,
		array(
			'human_url' => untrailingslashit( $human_base ),
			'pet_url'   => untrailingslashit( $pet_base ),
		)
	);

	get_template_part(
		'template-parts/home/doorway-split',
		null,
		array(
			'human_url' => $human_base . 'womens-skincare/',
			'pet_url'   => $pet_base . 'topical-skin-care/',
		)
	);

	get_template_part(
		'template-parts/home/concern-lanes',
		null,
		array(
			'human_base' => $human_base,
			'pet_base'   => $pet_base,
		)
	);

	get_template_part(
		'template-parts/home/ingredient-authority',
		null,
		array(
			'learn_url' => $learn_url,
		)
	);

	get_template_part(
		'template-parts/home/social-proof',
		null,
		array(
			'stories_url'  => $learn_url,
			'partners_url' => $learn_url,
		)
	);
	?>

</main>

<?php
get_footer();
