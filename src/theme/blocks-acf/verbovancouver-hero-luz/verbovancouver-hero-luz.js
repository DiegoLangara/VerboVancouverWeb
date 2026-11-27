( function( $ ) {

  // Barras de luz que se encienden desde el suelo, travesaño que forma la cruz,
  // "respiración" de la luz y apertura al hacer scroll (como entrar al santuario).
  function initHeroLuz( hero ) {
    if ( hero.dataset.heroLuzReady || typeof window.gsap === 'undefined' ) return;
    hero.dataset.heroLuzReady = '1';

    var gsap = window.gsap;
    var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
    if ( reduce ) return;

    var bars = hero.querySelectorAll( '.hero-luz__bar' );
    var beam = hero.querySelector( '.hero-luz__beam' );
    var copy = hero.querySelectorAll( '.hero-luz__eyebrow, .hero-luz__heading, .hero-luz__text, .hero-luz__actions' );

    gsap.timeline( { defaults: { ease: 'expo.out' } } )
      .from( bars, { scaleY: 0, opacity: 0, duration: 1.6, stagger: { each: 0.06, from: 'center' } } )
      .from( beam, { scaleX: 0, opacity: 0, duration: 1.2 }, '-=0.8' )
      .from( copy, { y: 30, opacity: 0, duration: 1, stagger: 0.1 }, '-=0.9' );

    gsap.to( bars, {
      opacity: function() { return gsap.utils.random( 0.35, 0.9 ); },
      duration: function() { return gsap.utils.random( 2, 4 ); },
      repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 2,
      stagger: { each: 0.2, from: 'random' }
    } );

    if ( window.ScrollTrigger && !hero.classList.contains( 'is-preview' ) ) {
      gsap.registerPlugin( window.ScrollTrigger );
      gsap.to( hero.querySelector( '.hero-luz__lights' ), {
        scale: 1.6, opacity: 0.25, ease: 'none',
        scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true }
      } );
    }
  }

  $( document ).ready( function() {
    document.querySelectorAll( '[data-hero-luz]' ).forEach( initHeroLuz );
  } );

  // Vista previa en el editor de bloques.
  if ( window.acf ) {
    window.acf.addAction( 'render_block_preview/type=hero-luz', function( $block ) {
      $block[0].querySelectorAll( '[data-hero-luz]' ).forEach( initHeroLuz );
    } );
  }

} )( jQuery );
