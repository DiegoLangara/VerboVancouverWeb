( function( $ ) {

  // Barras de luz que se encienden desde el suelo, travesaño que forma la cruz,
  // "respiración" de la luz y apertura al hacer scroll (como entrar al santuario).
  // En móvil: la cruz completa ocupa la pantalla con el antetítulo debajo, y al bajar
  // la luz sube y el resto del texto aparece poco a poco (sección fijada con scrub).
  function initHeroLuz( hero ) {
    if ( hero.dataset.heroLuzReady || typeof window.gsap === 'undefined' ) return;
    hero.dataset.heroLuzReady = '1';

    var gsap    = window.gsap;
    var reduce  = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
    var preview = hero.classList.contains( 'is-preview' );
    var hasST   = !!window.ScrollTrigger && !preview;

    var lights  = hero.querySelector( '.hero-luz__lights' );
    var bars    = hero.querySelectorAll( '.hero-luz__bar' );
    var beam    = hero.querySelector( '.hero-luz__beam' );
    var content = hero.querySelector( '.hero-luz__content' );
    var eyebrow = hero.querySelector( '.hero-luz__eyebrow' );
    var rest    = hero.querySelectorAll( '.hero-luz__heading, .hero-luz__text, .hero-luz__actions' );
    var scroll  = hero.querySelector( '.hero-luz__scroll' );

    if ( hasST ) gsap.registerPlugin( window.ScrollTrigger );

    // Movimiento reducido: solo un encendido suave, sin desplazamientos.
    if ( reduce ) {
      gsap.from( [ lights, content ], { opacity: 0, duration: 0.8, stagger: 0.2, ease: 'power1.out' } );
      return;
    }

    var mm = gsap.matchMedia();

    // Escritorio y tablet
    mm.add( '(min-width: 782px)', function() {
      gsap.timeline( { defaults: { ease: 'expo.out' } } )
        .from( bars, { scaleY: 0, opacity: 0, duration: 1.6, stagger: { each: 0.06, from: 'center' } } )
        .from( beam, { scaleX: 0, opacity: 0, duration: 1.2 }, '-=0.8' )
        .from( [ eyebrow ].concat( Array.prototype.slice.call( rest ) ), { y: 30, opacity: 0, duration: 1, stagger: 0.1 }, '-=0.9' );

      if ( hasST ) {
        gsap.to( lights, {
          scale: 1.6, opacity: 0.25, ease: 'none',
          scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true }
        } );
      }
    } );

    // Móvil
    mm.add( '(max-width: 781px)', function() {
      gsap.timeline( { defaults: { ease: 'expo.out' } } )
        .from( bars, { scaleY: 0, opacity: 0, duration: 1.5, stagger: { each: 0.05, from: 'center' } } )
        .from( beam, { scaleX: 0, opacity: 0, duration: 1.1 }, '-=0.8' )
        .from( [ eyebrow, scroll ], { y: 20, opacity: 0, duration: 0.9, stagger: 0.1 }, '-=0.7' );

      if ( !hasST ) return;

      hero.classList.add( 'is-sequenced' );
      gsap.set( rest, { opacity: 0, y: 40 } );

      // Cuánto debe subir el bloque de texto para que entre completo en pantalla
      function lift() {
        var r = content.getBoundingClientRect();
        var overflow = ( r.top + content.scrollHeight ) - ( window.innerHeight - 32 );
        return -Math.max( 0, overflow );
      }

      var seq = gsap.timeline( {
        scrollTrigger: {
          trigger: hero,
          start: 'top top',
          end: '+=120%',
          pin: true,
          scrub: 0.6,
          invalidateOnRefresh: true
        }
      } )
        .to( lights, { yPercent: -28, scale: 0.86, opacity: 0.5, ease: 'none', duration: 1 }, 0 )
        .to( content, { y: lift, ease: 'none', duration: 1 }, 0 )
        .to( scroll, { opacity: 0, duration: 0.2 }, 0 )
        .add( function() {}, 1 );

      // Título, bajada y botones aparecen uno tras otro mientras se baja
      Array.prototype.forEach.call( rest, function( el, i ) {
        seq.to( el, { opacity: 1, y: 0, duration: 0.35 }, 0.1 + i * 0.25 );
      } );

      return function() { hero.classList.remove( 'is-sequenced' ); };
    } );

    // Respiración de la luz, en todos los tamaños
    gsap.to( bars, {
      opacity: function() { return gsap.utils.random( 0.35, 0.9 ); },
      duration: function() { return gsap.utils.random( 2, 4 ); },
      repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 2,
      stagger: { each: 0.2, from: 'random' }
    } );
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
