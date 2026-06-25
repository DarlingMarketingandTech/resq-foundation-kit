/**
 * Cookie consent banner (Phase 10 H3).
 *
 * Records accept/decline in a cookie and dispatches a `resq-consent` event so
 * consent-gated scripts (e.g. analytics added in Phase 11) can react without a
 * page reload.
 *
 * @package ResQ_Clean_Pro
 */

( function () {
	'use strict';

	var banner = document.getElementById( 'resq-cookie-consent' );
	if ( ! banner ) {
		return;
	}

	var config    = window.resqCookieConsent || {};
	var cookieName = config.cookieName || 'resq_cookie_consent';
	var cookieDays = parseInt( config.cookieDays, 10 ) || 180;

	function setCookie( name, value, days ) {
		var date = new Date();
		date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
		document.cookie = name + '=' + encodeURIComponent( value ) +
			'; expires=' + date.toUTCString() + '; path=/; SameSite=Lax';
	}

	function choose( decision ) {
		setCookie( cookieName, decision, cookieDays );
		banner.classList.remove( 'is-visible' );

		try {
			document.dispatchEvent( new CustomEvent( 'resq-consent', { detail: { consent: decision } } ) );
		} catch ( e ) {
			// CustomEvent constructor unsupported — consent is still cookie-persisted.
		}

		if ( banner.parentNode ) {
			banner.parentNode.removeChild( banner );
		}
	}

	var buttons = banner.querySelectorAll( '[data-resq-consent]' );
	Array.prototype.forEach.call( buttons, function ( btn ) {
		btn.addEventListener( 'click', function () {
			choose( btn.getAttribute( 'data-resq-consent' ) === 'accepted' ? 'accepted' : 'declined' );
		} );
	} );

	// Reveal after paint so the banner can transition in.
	window.requestAnimationFrame( function () {
		banner.classList.add( 'is-visible' );
	} );
}() );
