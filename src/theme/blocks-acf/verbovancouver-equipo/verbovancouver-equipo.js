( function() {

  // Equipo: abre la biografía de cada persona en un <dialog> nativo.
  // Esc cierra (lo hace el navegador), igual que un clic fuera de la ventana o en la X.
  function initEquipo( section ) {
    if ( section.dataset.ready ) return;
    section.dataset.ready = '1';

    section.querySelectorAll( '[data-equipo-open]' ).forEach( function( btn ) {
      var dialog = document.getElementById( btn.getAttribute( 'data-equipo-open' ) );
      if ( !dialog || typeof dialog.showModal !== 'function' ) return;

      // El <dialog> se mueve al <body> para que ninguna transformación de la página lo recorte.
      document.body.appendChild( dialog );

      btn.addEventListener( 'click', function() {
        dialog.showModal();
        document.documentElement.classList.add( 'vv-dialog-open' );
      } );

      dialog.addEventListener( 'click', function( e ) {
        if ( e.target === dialog || e.target.closest( '[data-equipo-close]' ) ) dialog.close();
      } );

      dialog.addEventListener( 'close', function() {
        document.documentElement.classList.remove( 'vv-dialog-open' );
        btn.focus();
      } );
    } );
  }

  function init() {
    document.querySelectorAll( '[data-equipo]' ).forEach( initEquipo );
  }

  if ( document.readyState === 'loading' ) document.addEventListener( 'DOMContentLoaded', init );
  else init();

} )();
