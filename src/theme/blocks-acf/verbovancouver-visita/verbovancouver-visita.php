<?php

/**
 * Planea tu visita: horario, dirección, mapa y cuenta regresiva al próximo servicio.
 */

$fields = get_fields();

$classes = [ 'wp-block-vv-visita', 'alignfull' ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow  = $fields['eyebrow'] ?? 'Planea tu visita';
$heading  = $fields['heading'] ?? 'Te esperamos el sábado';
$text     = $fields['text'] ?? 'Llega unos minutos antes: te recibimos en la entrada, hay estacionamiento gratuito y un espacio seguro para los niños.';
$day      = isset( $fields['day'] ) && '' !== $fields['day'] ? (int) $fields['day'] : 6;
$time     = $fields['time'] ?? '18:00';
$address  = $fields['address'] ?? '7200 Cariboo Road, Burnaby BC';
$img      = !empty( $fields['image'] ) ? wp_get_attachment_image_url( $fields['image'], 'large' ) : vv_theme_image( 'home-visita.jpg' );
$whatsapp = preg_replace( '/\D/', '', $fields['whatsapp'] ?? '' );
$days     = [ 'Domingos', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábados' ];
$time_ts  = strtotime( '2000-01-01 ' . $time );
$time_lbl = $time_ts ? date( 'g:i a', $time_ts ) : $time;
$maps     = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $address );
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>" data-visita data-day="<?php print esc_attr( $day ); ?>" data-time="<?php print esc_attr( $time ); ?>">
  <div class="visita__grid vv-wrap">
    <div class="visita__media" data-reveal="mask">
      <img src="<?php print esc_url( $img ); ?>" alt="<?php print esc_attr( 'Entrada de la iglesia en ' . $address ); ?>" loading="lazy">
    </div>
    <div class="visita__info">
      <p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p>
      <h2 class="vv-title" data-reveal="split"><?php print esc_html( $heading ); ?></h2>
      <p class="vv-lead" data-reveal><?php print esc_html( $text ); ?></p>

      <dl class="visita__facts" data-reveal>
        <div><dt>Cuándo</dt><dd><?php print esc_html( $days[ $day ] . ' · ' . $time_lbl ); ?></dd></div>
        <div><dt>Dónde</dt><dd><?php print esc_html( $address ); ?></dd></div>
      </dl>

      <div class="visita__countdown" aria-live="off" data-reveal>
        <span class="visita__countdown-label">Próximo servicio en</span>
        <span class="visita__countdown-value" data-countdown>—</span>
      </div>

      <div class="visita__actions" data-reveal>
        <a class="vv-btn vv-btn--light" href="<?php print esc_url( $maps ); ?>" target="_blank" rel="noopener">Cómo llegar</a>
        <?php if ( $whatsapp ) : ?>
          <a class="vv-btn vv-btn--line" href="<?php print esc_url( 'https://wa.me/' . $whatsapp ); ?>" target="_blank" rel="noopener">Escríbenos por WhatsApp</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
