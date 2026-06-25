<?php
/**
 * Cookie consent banner (Phase 10 H3).
 *
 * Site-wide consent prompt. Sets a consent cookie that analytics tags (Phase 11
 * F1) gate on via resq_theme_has_analytics_consent(). Neutral copy; the linked
 * privacy policy page is owner/legal content.
 *
 * @package ResQ_Clean_Pro
 */

defined( 'ABSPATH' ) || exit;

$privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
?>
<div
	id="resq-cookie-consent"
	class="resq-cookie-consent"
	role="region"
	aria-label="<?php esc_attr_e( 'Cookie consent', 'resq-clean-pro' ); ?>"
>
	<div class="resq-cookie-consent__inner resq-container">
		<p class="resq-cookie-consent__text">
			<?php esc_html_e( 'We use cookies to run this site and, with your consent, to measure traffic and improve your experience.', 'resq-clean-pro' ); ?>
			<?php if ( $privacy_url ) : ?>
				<a class="resq-cookie-consent__link" href="<?php echo esc_url( $privacy_url ); ?>">
					<?php esc_html_e( 'Privacy Policy', 'resq-clean-pro' ); ?>
				</a>
			<?php endif; ?>
		</p>
		<div class="resq-cookie-consent__actions">
			<button type="button" class="resq-cookie-consent__decline btn btn--secondary" data-resq-consent="declined">
				<?php esc_html_e( 'Decline', 'resq-clean-pro' ); ?>
			</button>
			<button type="button" class="resq-cookie-consent__accept btn btn--primary" data-resq-consent="accepted">
				<?php esc_html_e( 'Accept', 'resq-clean-pro' ); ?>
			</button>
		</div>
	</div>
</div>
