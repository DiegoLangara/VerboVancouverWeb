( function( $ ) {

  // Cuenta regresiva al próximo servicio (hora de Vancouver), o "¡Estamos en vivo!" durante las 2 horas del servicio.
  function nextService( day, hh, mm ) {
    var now = new Date( new Date().toLocaleString( 'en-US', { timeZone: 'America/Vancouver' } ) );
    var target = new Date( now );
    target.setHours( hh, mm, 0, 0 );
    var diffDays = ( day - now.getDay() + 7 ) % 7;
    target.setDate( now.getDate() + diffDays );
    var end = new Date( target.getTime() + 2 * 3600 * 1000 );
    if ( diffDays === 0 && now >= target && now < end ) return { live: true };
    if ( target <= now ) target.setDate( target.getDate() + 7 );
    return { live: false, ms: target - now };
  }

  function initVisita( section ) {
    if ( section.dataset.ready ) return;
    section.dataset.ready = '1';
    var out = section.querySelector( '[data-countdown]' );
    var parts = ( section.dataset.time || '18:00' ).split( ':' );
    var day = parseInt( section.dataset.day, 10 );

    function tick() {
      var r = nextService( day, parseInt( parts[0], 10 ), parseInt( parts[1] || 0, 10 ) );
      if ( r.live ) { out.textContent = '¡Estamos en vivo!'; return; }
      var s = Math.floor( r.ms / 1000 );
      var d = Math.floor( s / 86400 ), h = Math.floor( s % 86400 / 3600 ), m = Math.floor( s % 3600 / 60 );
      out.textContent = ( d ? d + 'd ' : '' ) + h + 'h ' + ( m < 10 ? '0' : '' ) + m + 'm';
    }
    tick();
    setInterval( tick, 30000 );
  }

  $( document ).ready( function() {
    document.querySelectorAll( '[data-visita]' ).forEach( initVisita );
  } );

} )( jQuery );
