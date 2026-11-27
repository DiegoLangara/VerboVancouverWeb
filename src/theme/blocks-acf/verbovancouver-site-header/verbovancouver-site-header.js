( function() {
  'use strict';

  function init() {
    var header = document.querySelector( '[data-vv-header]' );
    if ( !header || header.dataset.ready ) return;
    header.dataset.ready = '1';

    var root     = document.documentElement;
    var toggle   = header.querySelector( '[data-vv-menu-toggle]' );
    var menu     = header.querySelector( '[data-vv-menu]' );
    var label    = toggle ? toggle.querySelector( '.screen-reader-text' ) : null;
    var progress = header.querySelector( '.vv-header__progress' );
    var reduce   = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
    var ticking  = false;

    // Estado sólido + línea de progreso de lectura
    function onScroll() {
      ticking = false;
      var y   = window.pageYOffset;
      var max = document.documentElement.scrollHeight - window.innerHeight;
      header.classList.toggle( 'is-solid', y > 24 );
      if ( progress ) progress.style.transform = 'scaleX(' + ( max > 0 ? Math.min( 1, y / max ) : 0 ) + ')';
    }
    window.addEventListener( 'scroll', function() {
      if ( !ticking ) { ticking = true; window.requestAnimationFrame( onScroll ); }
    }, { passive: true } );
    onScroll();

    if ( !toggle || !menu ) return;

    function focusables() {
      return Array.prototype.slice.call( menu.querySelectorAll( 'a[href], button:not([disabled])' ) );
    }

    function open() {
      menu.hidden = false;
      // Forzar reflow para que la transición de entrada corra
      void menu.offsetWidth;
      root.classList.add( 'vv-menu-open' );
      toggle.setAttribute( 'aria-expanded', 'true' );
      if ( label ) label.textContent = 'Cerrar menú';
      var first = focusables()[0];
      if ( first ) first.focus( { preventScroll: true } );
    }

    function close( returnFocus ) {
      root.classList.remove( 'vv-menu-open' );
      toggle.setAttribute( 'aria-expanded', 'false' );
      if ( label ) label.textContent = 'Abrir menú';
      window.setTimeout( function() {
        if ( !root.classList.contains( 'vv-menu-open' ) ) menu.hidden = true;
      }, reduce ? 0 : 450 );
      if ( returnFocus !== false ) toggle.focus( { preventScroll: true } );
    }

    toggle.addEventListener( 'click', function() {
      root.classList.contains( 'vv-menu-open' ) ? close() : open();
    } );

    menu.addEventListener( 'click', function( e ) {
      if ( e.target.closest( 'a' ) ) close( false );
    } );

    document.addEventListener( 'keydown', function( e ) {
      if ( !root.classList.contains( 'vv-menu-open' ) ) return;
      if ( 'Escape' === e.key ) { e.preventDefault(); close(); return; }
      if ( 'Tab' !== e.key ) return;
      // Mantener el foco dentro del menú y el botón de cierre
      var items = [ toggle ].concat( focusables() );
      var i = items.indexOf( document.activeElement );
      if ( e.shiftKey && i <= 0 ) { e.preventDefault(); items[ items.length - 1 ].focus(); }
      else if ( !e.shiftKey && i === items.length - 1 ) { e.preventDefault(); items[0].focus(); }
    } );

    window.matchMedia( '(min-width: 1100px)' ).addEventListener( 'change', function( mq ) {
      if ( mq.matches && root.classList.contains( 'vv-menu-open' ) ) close( false );
    } );
  }

  if ( 'loading' === document.readyState ) document.addEventListener( 'DOMContentLoaded', init );
  else init();
} )();
