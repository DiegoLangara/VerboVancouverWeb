<?php

/**
 * Siguiente paso: tres "puertas" grandes que se iluminan al pasar el cursor.
 */

$fields = get_fields();

$classes = [ 'wp-block-vv-pasos', 'alignfull' ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = $fields['eyebrow'] ?? 'Tu siguiente paso';
$heading = $fields['heading'] ?? 'Bienvenidos a la familia';
$items   = !empty( $fields['items'] ) ? $fields['items'] : [
  [ 'title' => 'Visitar', 'text' => 'Ven este sábado a las 6:00 pm.', 'link' => [ 'url' => '/#visitanos', 'title' => 'Planear mi visita' ] ],
  [ 'title' => 'Conectar', 'text' => 'Únete a un grupo en casa cerca de ti.', 'link' => [ 'url' => '/igho/', 'title' => 'Unirme a un grupo' ] ],
  [ 'title' => 'Dar', 'text' => 'Sé parte de lo que Dios hace en Vancouver.', 'link' => [ 'url' => 'https://tithe.ly/give?c=1647510', 'title' => 'Donar en línea' ] ],
];
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>">
  <span class="vv-lightline" aria-hidden="true"></span>
  <div class="pasos__head vv-wrap">
    <p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p>
    <h2 class="vv-title" data-reveal="split"><?php print esc_html( $heading ); ?></h2>
  </div>
  <div class="pasos__doors vv-wrap">
    <?php foreach ( $items as $i => $item ) :
      $url = $item['link']['url'] ?? '#'; ?>
      <a class="pasos__door" href="<?php print esc_url( $url ); ?>" data-reveal>
        <span class="pasos__num"><?php printf( '%02d', $i + 1 ); ?></span>
        <span class="pasos__title"><?php print esc_html( $item['title'] ); ?></span>
        <span class="pasos__text"><?php print esc_html( $item['text'] ); ?></span>
        <span class="pasos__cta"><?php print esc_html( $item['link']['title'] ?? 'Ir' ); ?> →</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
