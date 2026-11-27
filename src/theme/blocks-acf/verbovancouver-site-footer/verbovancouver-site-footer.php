<?php

/**
 * Pie del sitio: una invitación final bajo la luz, los datos para llegar,
 * enlaces, redes y el sello VERBO que se levanta al llegar al final.
 */

$fields  = get_fields() ?: [];
$info    = vv_church_info();
$heading = !empty( $fields['heading'] ) ? $fields['heading'] : 'Te esperamos el sábado';
$text    = !empty( $fields['text'] ) ? $fields['text'] : 'Llega como estás. Hay un lugar para ti y para tu familia.';
$tagline = !empty( $fields['tagline'] ) ? $fields['tagline'] : 'Iglesia cristiana en español en Burnaby. Una familia donde la luz de Cristo habita.';

$links = [];
if ( !empty( $fields['links'] ) ) {
  foreach ( $fields['links'] as $row ) if ( !empty( $row['link']['url'] ) ) $links[] = $row['link'];
}
if ( !$links ) {
  $links = [
    [ 'url' => '/nosotros/', 'title' => 'Nosotros' ],
    [ 'url' => '/predicas/', 'title' => 'Prédicas' ],
    [ 'url' => '/ministerios/', 'title' => 'Ministerios' ],
    [ 'url' => '/eventos/', 'title' => 'Eventos' ],
    [ 'url' => '/igho/', 'title' => 'Grupos en casa' ],
    [ 'url' => '/contacto/', 'title' => 'Contacto' ],
  ];
}

$tel     = preg_replace( '/[^0-9+]/', '', $info['phone'] );
$privacy = get_privacy_policy_url() ?: '/privacy-policy/';
$classes = [ 'vv-footer' ];
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';
?>
<div class="<?php print esc_attr( implode( ' ', $classes ) ); ?>" data-vv-footer>

  <section class="vv-footer__invite" aria-labelledby="vv-footer-heading">
    <div class="vv-footer__lights" aria-hidden="true">
      <?php for ( $i = 0; $i < 9; $i++ ) : ?><span></span><?php endfor; ?>
    </div>
    <div class="vv-wrap vv-footer__invite-inner">
      <p class="vv-eyebrow" data-reveal><?php print esc_html( $info['service'] ); ?></p>
      <h2 id="vv-footer-heading" class="vv-title vv-footer__heading" data-reveal="split"><?php print esc_html( $heading ); ?></h2>
      <p class="vv-footer__text" data-reveal><?php print esc_html( $text ); ?></p>
      <div class="vv-footer__buttons" data-reveal>
        <a class="vv-footer__btn vv-footer__btn--primary" href="<?php print esc_url( home_url( '/#visitanos' ) ); ?>">Planea tu visita</a>
        <a class="vv-footer__btn" href="<?php print esc_url( $info['map_url'] ); ?>" target="_blank" rel="noopener">Cómo llegar</a>
      </div>
    </div>
  </section>

  <div class="vv-wrap vv-footer__grid">
    <div class="vv-footer__brand">
      <a class="vv-footer__logo" href="<?php print esc_url( home_url( '/' ) ); ?>" aria-label="Verbo Vancouver, inicio"><?php print vv_logo_svg( 'vv-logo' ); ?></a>
      <p class="skip-observing"><?php print esc_html( $tagline ); ?></p>
      <ul class="vv-social">
        <?php foreach ( $info['social'] as $net => $url ) : ?>
          <li><a href="<?php print esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php print esc_attr( ucfirst( $net ) ); ?>"><?php print vv_social_icon( $net ); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="vv-footer__col">
      <h3 class="vv-footer__label">Visítanos</h3>
      <address>
        <a href="<?php print esc_url( $info['map_url'] ); ?>" target="_blank" rel="noopener"><?php print esc_html( $info['address'] ); ?></a>
      </address>
      <p class="skip-observing footer-service"><span class="vv-footer__live" aria-hidden="true"></span><?php print esc_html( $info['service'] ); ?></p>
    </div>

    <nav class="vv-footer__col" aria-label="Explora">
      <h3 class="vv-footer__label">Explora</h3>
      <ul>
        <?php foreach ( $links as $link ) : ?>
          <li><a href="<?php print esc_url( $link['url'] ); ?>"<?php if ( !empty( $link['target'] ) ) print ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"'; ?>><?php print esc_html( $link['title'] ); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="vv-footer__col">
      <h3 class="vv-footer__label">Contacto</h3>
      <ul>
        <li><a href="tel:<?php print esc_attr( $tel ); ?>"><?php print esc_html( $info['phone'] ); ?></a></li>
        <li><a href="mailto:<?php print esc_attr( $info['email'] ); ?>"><?php print str_replace( '@', '@<wbr>', esc_html( $info['email'] ) ); ?></a></li>
      </ul>
      <a class="vv-footer__give" href="<?php print esc_url( $info['give_url'] ); ?>" target="_blank" rel="noopener">Dar en línea →</a>
    </div>
  </div>

  <div class="vv-footer__seal" aria-hidden="true" data-vv-seal>
    <?php print vv_wordmark_svg( 'vv-wordmark' ); ?>
  </div>

  <div class="vv-wrap vv-footer__bottom">
    <p class="skip-observing">© <?php print esc_html( wp_date( 'Y' ) ); ?> Iglesia Cristiana Verbo Vancouver</p>
    <p class="skip-observing"><a href="<?php print esc_url( $privacy ); ?>">Política de privacidad</a><span aria-hidden="true"> · </span>Sitio por <a href="https://diego-almeida.ca" target="_blank" rel="noopener">Diego Almeida</a></p>
  </div>
</div>
