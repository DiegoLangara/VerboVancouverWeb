<?php

/**
 * Cabecera del sitio: transparente sobre el hero, sólida al bajar,
 * con una línea de luz que marca el progreso de lectura y un menú móvil a pantalla completa.
 */

$fields = get_fields() ?: [];
$info   = vv_church_info();

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
    [ 'url' => '/contacto/', 'title' => 'Contacto' ],
  ];
}
$cta = !empty( $fields['cta']['url'] ) ? $fields['cta'] : [ 'url' => '/#visitanos', 'title' => 'Planea tu visita' ];

$current = untrailingslashit( strtok( $_SERVER['REQUEST_URI'] ?? '/', '?' ) );
$classes = [ 'vv-header' ];
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$link_attrs = function( $link ) use ( $current ) {
  $out  = ' href="' . esc_url( $link['url'] ) . '"';
  if ( !empty( $link['target'] ) ) $out .= ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"';
  $path = untrailingslashit( (string) wp_parse_url( $link['url'], PHP_URL_PATH ) );
  if ( $path && $path === $current ) $out .= ' aria-current="page"';
  return $out;
};
?>
<div class="<?php print esc_attr( implode( ' ', $classes ) ); ?>" data-vv-header>
  <div class="vv-header__bar">
    <a class="vv-header__logo skip-observing" href="<?php print esc_url( home_url( '/' ) ); ?>" aria-label="Verbo Vancouver, inicio">
      <?php print vv_logo_svg( 'vv-logo' ); ?>
    </a>

    <nav class="vv-header__nav" aria-label="Menú principal">
      <ul>
        <?php foreach ( $links as $link ) : ?>
          <li><a<?php print $link_attrs( $link ); ?>><span><?php print esc_html( $link['title'] ); ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="vv-header__actions">
      <a class="vv-header__give" href="<?php print esc_url( $info['give_url'] ); ?>" target="_blank" rel="noopener">Dar</a>
      <a class="vv-header__cta"<?php print $link_attrs( $cta ); ?>>
        <span class="vv-header__cta-dot" aria-hidden="true"></span><?php print esc_html( $cta['title'] ); ?>
      </a>
      <button class="vv-header__toggle" type="button" aria-expanded="false" aria-controls="vv-menu" data-vv-menu-toggle>
        <span class="screen-reader-text">Abrir menú</span>
        <span class="vv-header__burger" aria-hidden="true"><i></i><i></i></span>
      </button>
    </div>
  </div>
  <span class="vv-header__progress" aria-hidden="true"></span>

  <div class="vv-menu" id="vv-menu" role="dialog" aria-modal="true" aria-label="Menú" hidden data-vv-menu>
    <div class="vv-menu__lights" aria-hidden="true">
      <?php for ( $i = 0; $i < 7; $i++ ) : ?><span></span><?php endfor; ?>
    </div>
    <nav class="vv-menu__nav" aria-label="Menú móvil">
      <ol>
        <?php foreach ( $links as $i => $link ) : ?>
          <li style="--i:<?php print (int) $i; ?>"><a<?php print $link_attrs( $link ); ?>><small><?php printf( '%02d', $i + 1 ); ?></small><?php print esc_html( $link['title'] ); ?></a></li>
        <?php endforeach; ?>
      </ol>
    </nav>
    <div class="vv-menu__foot">
      <a class="vv-menu__cta"<?php print $link_attrs( $cta ); ?>><?php print esc_html( $cta['title'] ); ?> →</a>
      <p class="skip-observing"><strong><?php print esc_html( $info['service'] ); ?></strong><br><?php print esc_html( $info['address'] ); ?></p>
      <ul class="vv-social">
        <?php foreach ( $info['social'] as $net => $url ) : ?>
          <li><a href="<?php print esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php print esc_attr( ucfirst( $net ) ); ?>"><?php print vv_social_icon( $net ); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
