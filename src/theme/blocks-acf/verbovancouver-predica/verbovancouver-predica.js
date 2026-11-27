( function( $ ) {

  // Carga el iframe de YouTube solo cuando la persona pulsa reproducir.
  $( document ).on( 'click', '.wp-block-vv-predica .predica__facade[data-video]', function( e ) {
    if ( this.closest( '.is-preview' ) ) return;
    e.preventDefault();
    var id = this.getAttribute( 'data-video' );
    var iframe = document.createElement( 'iframe' );
    iframe.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
    iframe.title = this.getAttribute( 'aria-label' ) || 'Prédica';
    iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
    iframe.allowFullscreen = true;
    var frame = document.createElement( 'div' );
    frame.className = 'predica__facade is-playing';
    frame.appendChild( iframe );
    this.replaceWith( frame );
  } );

} )( jQuery );
