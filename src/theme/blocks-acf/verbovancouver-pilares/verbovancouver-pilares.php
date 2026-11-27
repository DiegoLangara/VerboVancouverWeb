<?php

/**
 * Pilares: Adoración, Comunidad y Crecimiento como paneles que avanzan en horizontal con el scroll.
 */

$fields = get_fields();

$classes = [ 'wp-block-vv-pilares', 'alignfull' ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = $fields['eyebrow'] ?? 'Lo que nos mueve';
$heading = $fields['heading'] ?? 'Tres pilares, una familia';
$items   = !empty( $fields['items'] ) ? $fields['items'] : [
  [ 'title' => 'Adoración', 'text' => 'Cultos vibrantes en español. Cada sábado celebramos a Dios con gozo, música y libertad.', 'image' => 'home-adoracion.jpg', 'link' => [ 'url' => '/reuniones/', 'title' => 'Ver reuniones' ] ],
  [ 'title' => 'Comunidad', 'text' => 'Grupos en casa (IGHO), familias, jóvenes y niños. Nadie camina solo.', 'image' => 'home-comunidad.jpg', 'link' => [ 'url' => '/comunidad/', 'title' => 'Encuentra tu grupo' ] ],
  [ 'title' => 'Crecimiento', 'text' => 'Predicaciones y estudios bíblicos prácticos para la vida diaria.', 'image' => 'home-crecimiento.jpg', 'link' => [ 'url' => '/predicas/', 'title' => 'Escuchar prédicas' ] ],
];
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>" data-pilares>
  <div class="pilares__intro vv-wrap">
    <p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p>
    <h2 class="vv-title" data-reveal="split"><?php print esc_html( $heading ); ?></h2>
  </div>
  <div class="pilares__viewport">
    <ol class="pilares__track">
      <?php foreach ( $items as $i => $item ) :
        $img = is_numeric( $item['image'] ?? null ) ? wp_get_attachment_image_url( $item['image'], 'large' ) : ( !empty( $item['image'] ) ? vv_theme_image( $item['image'] ) : '' ); ?>
        <li class="pilares__panel">
          <?php if ( $img ) : ?><img class="pilares__img" src="<?php print esc_url( $img ); ?>" alt="" loading="lazy"><?php endif; ?>
          <div class="pilares__body">
            <span class="pilares__num"><?php printf( '%02d', $i + 1 ); ?></span>
            <h3 class="pilares__title"><?php print esc_html( $item['title'] ?? '' ); ?></h3>
            <p class="pilares__text"><?php print esc_html( $item['text'] ?? '' ); ?></p>
            <?php if ( !empty( $item['link']['url'] ) ) : ?>
              <a class="vv-btn vv-btn--line" href="<?php print esc_url( $item['link']['url'] ); ?>"><?php print esc_html( $item['link']['title'] ); ?></a>
            <?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
