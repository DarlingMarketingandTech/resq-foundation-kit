/**
 * Age verification gate — cookie-based soft gate for CBD surfaces (Phase 10 A4).
 *
 * Behaviour:
 *  - Locks scroll and traps focus inside the modal while shown.
 *  - Confirm sets a cookie and dismisses the gate.
 *  - Decline navigates away to the configured exit URL.
 *  - No Escape/overlay dismissal — the gate must be answered.
 *
 * @package ResQ_Clean_Pro
 */

( function () {
	'use strict';

	var gate = document.getElementById( 'resq-age-gate' );
	if ( ! gate ) {
		return;
	}

	var config    = window.resqAgeGate || {};
	var cookieName = config.cookieName || 'resq_age_confirmed';
	var cookieDays = parseInt( config.cookieDays, 10 ) || 30;
	var exitUrl    = config.exitUrl || '/';

	var panel     = gate.querySelector( '.resq-age-gate__panel' );
	var confirmBtn = gate.querySelector( '[data-resq-age-confirm]' );
	var declineBtn = gate.querySelector( '[data-resq-age-decline]' );
	var FOCUSABLE  = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

	function setCookie( name, value, days ) {
		var expires = '';
		if ( days ) {
			var date = new Date();
			date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
			expires = '; expires=' + date.toUTCString();
		}
		document.cookie = name + '=' + encodeURIComponent( value ) + expires + '; path=/; SameSite=Lax';
	}

	function show() {
		gate.classList.add( 'is-open' );
		document.body.classList.add( 'resq-age-gate-open' );
		if ( confirmBtn ) {
			confirmBtn.focus();
		}
		document.addEventListener( 'keydown', trapKeydown );
	}

	function dismiss() {
		gate.classList.remove( 'is-open' );
		document.body.classList.remove( 'resq-age-gate-open' );
		document.removeEventListener( 'keydown', trapKeydown );
		if ( gate.parentNode ) {
			gate.parentNode.removeChild( gate );
		}
	}

	function trapKeydown( event ) {
		if ( event.key !== 'Tab' || ! panel ) {
			return;
		}
		var focusable = Array.prototype.slice.call( panel.querySelectorAll( FOCUSABLE ) );
		if ( ! focusable.length ) {
			return;
		}
		var first = focusable[ 0 ];
		var last  = focusable[ focusable.length - 1 ];
		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	}

	if ( confirmBtn ) {
		confirmBtn.addEventListener( 'click', function () {
			setCookie( cookieName, 'confirmed', cookieDays );
			dismiss();
		} );
	}

	if ( declineBtn ) {
		declineBtn.addEventListener( 'click', function () {
			window.location.href = exitUrl;
		} );
	}

	show();
}() );
