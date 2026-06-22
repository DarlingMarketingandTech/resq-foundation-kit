/**
 * Mobile drawer and menu toggle behavior.
 *
 * @package ResQ_Clean_Pro
 */

( function () {
	'use strict';

	const drawer = document.getElementById( 'resq-mobile-drawer' );
	const toggle = document.getElementById( 'resq-menu-toggle' );

	if ( ! drawer || ! toggle ) {
		return;
	}

	const panel = drawer.querySelector( '.mobile-drawer__panel' );
	const overlay = drawer.querySelector( '.mobile-drawer__overlay' );
	const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

	function setOpen( isOpen ) {
		drawer.classList.toggle( 'is-open', isOpen );
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		document.body.style.overflow = isOpen ? 'hidden' : '';

		if ( isOpen && panel ) {
			const firstFocusable = panel.querySelector( focusableSelector );
			if ( firstFocusable ) {
				firstFocusable.focus();
			}
		}
	}

	toggle.addEventListener( 'click', function () {
		setOpen( ! drawer.classList.contains( 'is-open' ) );
	} );

	if ( overlay ) {
		overlay.addEventListener( 'click', function () {
			setOpen( false );
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && drawer.classList.contains( 'is-open' ) ) {
			setOpen( false );
			toggle.focus();
		}
	} );
}() );
