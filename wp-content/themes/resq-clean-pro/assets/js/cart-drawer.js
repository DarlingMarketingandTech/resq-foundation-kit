/**
 * Commerce cart drawer — open on add-to-cart, load suggestions via AJAX.
 *
 * Behaviour:
 *  - Listens for WooCommerce's `added_to_cart` JS event (AJAX add-to-cart).
 *  - On non-AJAX add-to-cart (page reload), reads ?added-to-cart query param.
 *  - Fetches suggestion cards from the resq_cart_drawer_suggestions AJAX action.
 *  - If suggestions exist, injects them and opens the drawer.
 *  - If no suggestions, still opens the drawer (shows cart/checkout actions only).
 *  - Escape key, overlay click, and close button all close the drawer.
 *  - Focus is trapped inside the panel while open; restored to trigger on close.
 *  - Never opens on the checkout or cart pages.
 *
 * @package ResQ_Clean_Pro
 */

( function () {
	'use strict';

	const DRAWER_ID   = 'resq-cart-drawer';
	const FOCUSABLE   = 'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])';

	const drawer      = document.getElementById( DRAWER_ID );
	if ( ! drawer ) return;

	const overlay     = drawer.querySelector( '.cart-drawer__overlay' );
	const panel       = drawer.querySelector( '.cart-drawer__panel' );
	const closeBtn    = drawer.querySelector( '.cart-drawer__close' );
	const suggestions = drawer.querySelector( '.cart-drawer__suggestions' );

	// Provided by wp_localize_script in inc/woocommerce.php.
	const config = window.resqCartDrawer || {};
	const ajaxUrl = config.ajaxUrl  || '';
	const nonce   = config.nonce    || '';
	const isCheckout = config.isCheckout || false;
	const isCart     = config.isCart     || false;

	// Don't activate on cart or checkout pages.
	if ( isCheckout || isCart ) return;

	// -------------------------------------------------------------------------
	// Open / close
	// -------------------------------------------------------------------------

	let lastTrigger = null;

	function open( triggerEl ) {
		lastTrigger = triggerEl || null;
		drawer.setAttribute( 'aria-hidden', 'false' );
		drawer.classList.add( 'is-open' );
		document.body.classList.add( 'cart-drawer-open' );
		trapFocus();
	}

	function close() {
		drawer.setAttribute( 'aria-hidden', 'true' );
		drawer.classList.remove( 'is-open' );
		document.body.classList.remove( 'cart-drawer-open' );
		if ( lastTrigger && typeof lastTrigger.focus === 'function' ) {
			lastTrigger.focus();
		}
		lastTrigger = null;
	}

	// -------------------------------------------------------------------------
	// Focus trap
	// -------------------------------------------------------------------------

	function trapFocus() {
		const focusable = Array.from( panel.querySelectorAll( FOCUSABLE ) );
		if ( ! focusable.length ) return;
		focusable[ 0 ].focus();

		panel.addEventListener( 'keydown', handleTrapKeydown );
	}

	function handleTrapKeydown( event ) {
		if ( event.key !== 'Tab' ) return;

		const focusable = Array.from( panel.querySelectorAll( FOCUSABLE ) );
		if ( ! focusable.length ) return;

		const first = focusable[ 0 ];
		const last  = focusable[ focusable.length - 1 ];

		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	}

	// Release trap listener when closed so it doesn't pile up.
	drawer.addEventListener( 'transitionend', function () {
		if ( ! drawer.classList.contains( 'is-open' ) ) {
			panel.removeEventListener( 'keydown', handleTrapKeydown );
		}
	} );

	// -------------------------------------------------------------------------
	// Close triggers
	// -------------------------------------------------------------------------

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', close );
	}

	if ( overlay ) {
		overlay.addEventListener( 'click', close );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && drawer.classList.contains( 'is-open' ) ) {
			close();
		}
	} );

	// -------------------------------------------------------------------------
	// Suggestion loading
	// -------------------------------------------------------------------------

	function loadSuggestions( productId, onDone ) {
		if ( ! ajaxUrl || ! productId ) {
			if ( suggestions ) suggestions.innerHTML = '';
			if ( typeof onDone === 'function' ) onDone();
			return;
		}

		const params = new URLSearchParams( {
			action:     'resq_cart_drawer_suggestions',
			product_id: productId,
			nonce:      nonce,
		} );

		fetch( ajaxUrl, {
			method:  'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body:    params.toString(),
		} )
			.then( function ( r ) { return r.json(); } )
			.then( function ( data ) {
				if ( suggestions ) {
					suggestions.innerHTML = ( data && data.html ) ? data.html : '';
				}
			} )
			.catch( function () {
				if ( suggestions ) suggestions.innerHTML = '';
			} )
			.finally( function () {
				if ( typeof onDone === 'function' ) onDone();
			} );
	}

	// -------------------------------------------------------------------------
	// AJAX add-to-cart (WooCommerce fires added_to_cart on the body)
	// -------------------------------------------------------------------------

	document.body.addEventListener( 'added_to_cart', function ( event ) {
		// WooCommerce passes (fragments, cart_hash, $button) via jQuery trigger.
		// In vanilla JS the detail object may be undefined; extract from event.detail
		// or fall back to reading the last-added product from the button that was clicked.
		let productId = 0;

		if ( event.detail && event.detail.product_id ) {
			productId = parseInt( event.detail.product_id, 10 );
		}

		// WooCommerce also sets data-product_id on add-to-cart buttons.
		if ( ! productId ) {
			const btn = document.querySelector( '.add_to_cart_button.loading, .single_add_to_cart_button' );
			if ( btn ) {
				productId = parseInt( btn.getAttribute( 'data-product_id' ) || btn.getAttribute( 'data-product-id' ) || '0', 10 );
			}
		}

		loadSuggestions( productId, function () {
			open( document.activeElement );
		} );
	} );

	// WooCommerce fires a jQuery event — bridge it to a native CustomEvent so
	// the vanilla listener above fires on sites where jQuery wraps the event.
	if ( typeof jQuery !== 'undefined' ) {
		jQuery( document.body ).on( 'added_to_cart', function ( event, fragments, cartHash, $button ) {
			let productId = 0;
			if ( $button && $button.length ) {
				productId = parseInt( $button.data( 'product_id' ) || $button.data( 'product-id' ) || '0', 10 );
			}
			loadSuggestions( productId, function () {
				open( $button && $button[ 0 ] ? $button[ 0 ] : null );
			} );
		} );
	}

	// -------------------------------------------------------------------------
	// Non-AJAX add-to-cart (page reload with ?added-to-cart=ID in URL)
	// -------------------------------------------------------------------------

	( function () {
		const params   = new URLSearchParams( window.location.search );
		const addedId  = parseInt( params.get( 'added-to-cart' ) || '0', 10 );
		if ( ! addedId ) return;

		loadSuggestions( addedId, function () {
			open( null );
		} );
	}() );

}() );
