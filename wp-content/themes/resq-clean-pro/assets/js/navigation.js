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
	const closeBtn = drawer.querySelector( '.mobile-drawer__close' );
	const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

	function trapFocus( event ) {
		if ( event.key !== 'Tab' || ! panel ) return;

		const focusable = Array.from( panel.querySelectorAll( focusableSelector ) );
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

	function setOpen( isOpen ) {
		drawer.classList.toggle( 'is-open', isOpen );
		drawer.setAttribute( 'aria-hidden', isOpen ? 'false' : 'true' );
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		document.body.style.overflow = isOpen ? 'hidden' : '';

		if ( isOpen && panel ) {
			panel.addEventListener( 'keydown', trapFocus );
			const firstFocusable = panel.querySelector( focusableSelector );
			if ( firstFocusable ) {
				firstFocusable.focus();
			}
		} else {
			panel.removeEventListener( 'keydown', trapFocus );
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

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', function () {
			setOpen( false );
			toggle.focus();
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && drawer.classList.contains( 'is-open' ) ) {
			setOpen( false );
			toggle.focus();
		}
	} );

	/* ----------------------------------------------------------------------
	 * Mobile drawer — collapsible child sections (accordion)
	 * -------------------------------------------------------------------- */
	const groupToggles = drawer.querySelectorAll( '.mobile-drawer__group-toggle' );

	groupToggles.forEach( function ( groupToggle ) {
		groupToggle.addEventListener( 'click', function () {
			const sublistId = groupToggle.getAttribute( 'aria-controls' );
			const sublist   = sublistId ? document.getElementById( sublistId ) : null;
			if ( ! sublist ) return;

			const isExpanded = groupToggle.getAttribute( 'aria-expanded' ) === 'true';
			groupToggle.setAttribute( 'aria-expanded', isExpanded ? 'false' : 'true' );
			sublist.classList.toggle( 'is-expanded', ! isExpanded );
		} );
	} );
}() );

/* --------------------------------------------------------------------------
 * Desktop mega-menu — keyboard + Escape support (hover handled by CSS)
 * ------------------------------------------------------------------------ */
( function () {
	'use strict';

	const items = document.querySelectorAll( '.main-navigation__item--has-children' );
	if ( ! items.length ) return;

	items.forEach( function ( item ) {
		const trigger = item.querySelector( '.main-navigation__trigger' );
		if ( ! trigger ) return;

		function close() {
			item.setAttribute( 'data-open', 'false' );
			trigger.setAttribute( 'aria-expanded', 'false' );
		}

		function open() {
			items.forEach( function ( other ) {
				if ( other !== item ) {
					other.setAttribute( 'data-open', 'false' );
					const otherTrigger = other.querySelector( '.main-navigation__trigger' );
					if ( otherTrigger ) otherTrigger.setAttribute( 'aria-expanded', 'false' );
				}
			} );
			item.setAttribute( 'data-open', 'true' );
			trigger.setAttribute( 'aria-expanded', 'true' );
		}

		trigger.addEventListener( 'focus', open );

		trigger.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key ) {
				close();
			}
		} );

		item.addEventListener( 'focusout', function ( event ) {
			if ( ! item.contains( event.relatedTarget ) ) {
				close();
			}
		} );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			items.forEach( function ( item ) {
				item.setAttribute( 'data-open', 'false' );
				const trigger = item.querySelector( '.main-navigation__trigger' );
				if ( trigger ) trigger.setAttribute( 'aria-expanded', 'false' );
			} );
		}
	} );
}() );
