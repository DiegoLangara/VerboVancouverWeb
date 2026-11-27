( function() {
  'use strict';

  // El sello VERBO se levanta y se enciende a medida que llega el final de la página.
  function init() {
    var seal = document.querySelector( '[data-vv-seal]' );
    if ( !seal || seal.closest( '.is-preview' ) ) return;
    if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) return;
    if ( !window.gsap || !window.ScrollTrigger ) return;

    var gsap = window.gsap;
    gsap.registerPlugin( window.ScrollTrigger );

    gsap.fromTo( seal.querySelector( 'svg' ),
      { yPercent: 45, opacity: 0.15 },
      {
        yPercent: 0,
        opacity: 1,
        ease: 'none',
        scrollTrigger: { trigger: seal, start: 'top bottom', end: 'bottom bottom', scrub: 0.6 }
      }
    );

    gsap.fromTo( seal, { '--vv-seal-glow': 0 }, {
      '--vv-seal-glow': 1,
      ease: 'none',
      scrollTrigger: { trigger: seal, start: 'top 90%', end: 'bottom bottom', scrub: true }
    } );
  }

  if ( 'loading' === document.readyState ) document.addEventListener( 'DOMContentLoaded', init );
  else init();
} )();
