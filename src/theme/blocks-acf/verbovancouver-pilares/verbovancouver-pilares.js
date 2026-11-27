( function( $ ) {

  // En escritorio, la sección se ancla y los paneles avanzan en horizontal con el scroll.
  function initPilares( section ) {
    if ( section.dataset.ready || !window.gsap || !window.ScrollTrigger ) return;
    section.dataset.ready = '1';
    if ( section.classList.contains( 'is-preview' ) ) return;
    if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) return;

    var gsap = window.gsap;
    gsap.registerPlugin( window.ScrollTrigger );

    var mm = gsap.matchMedia();
    mm.add( '(min-width: 782px)', function() {
      var track = section.querySelector( '.pilares__track' );
      var distance = function() { return track.scrollWidth - window.innerWidth; };

      var tween = gsap.to( track, {
        x: function() { return -distance(); },
        ease: 'none',
        scrollTrigger: {
          trigger: section.querySelector( '.pilares__viewport' ),
          start: 'center center',
          end: function() { return '+=' + distance(); },
          pin: section,
          scrub: 1,
          invalidateOnRefresh: true
        }
      } );

      section.querySelectorAll( '.pilares__img' ).forEach( function( img ) {
        gsap.to( img, {
          xPercent: -8, ease: 'none',
          scrollTrigger: { trigger: img.parentNode, containerAnimation: tween, start: 'left right', end: 'right left', scrub: true }
        } );
      } );
    } );
  }

  $( document ).ready( function() {
    document.querySelectorAll( '[data-pilares]' ).forEach( initPilares );
  } );

} )( jQuery );
