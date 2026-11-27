<?php

/**
 * Comunidad: foto grande con revelado y lista de ministerios que se encienden al pasar.
 */

$fields = get_fields();

$classes = [ 'wp-block-vv-comunidad', 'alignfull' ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = $fields['eyebrow'] ?? 'Comunidad';
$heading = $fields['heading'] ?? 'Hay un lugar para ti';
$text    = $fields['text'] ?? 'Desde los más pequeños hasta los abuelos: crecemos juntos en grupos, ministerios y en las Iglesias en el Hogar (IGHO).';
$img     = !empty( $fields['image'] ) ? wp_get_attachment_image_url( $fields['image'], 'large' ) : vv_theme_image( 'home-familia.jpg' );
$items   = !empty( $fields['items'] ) ? $fields['items'] : [
  [ 'title' => 'IGHO', 'tag' => 'Iglesias en el hogar', 'url' => '/igho/' ],
  [ 'title' => 'Verbo Kids', 'tag' => '0 a 11 años', 'url' => '/ministerios/#kids' ],
  [ 'title' => 'Verbo Juniors', 'tag' => '12 a 14 años', 'url' => '/ministerios/#youth' ],
  [ 'title' => 'Verbo Youth', 'tag' => '15 a 19 años', 'url' => '/ministerios/#youth' ],
  [ 'title' => 'Matrimonios', 'tag' => 'Parejas', 'url' => '/ministerios/#matrimonios' ],
  [ 'title' => 'Mujeres y Hombres', 'tag' => 'Adultos', 'url' => '/ministerios/#mujeres' ],
  [ 'title' => 'Alabanza y Danza', 'tag' => 'Servir con talentos', 'url' => '/ministerios/#alabanza' ],
];
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>">
  <div class="comunidad__grid vv-wrap">
    <div class="comunidad__copy">
      <p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p>
      <h2 class="vv-title" data-reveal="split"><?php print esc_html( $heading ); ?></h2>
      <p class="vv-lead" data-reveal><?php print esc_html( $text ); ?></p>
      <div class="comunidad__media" data-reveal="mask">
        <img src="<?php print esc_url( $img ); ?>" alt="Congregación de Verbo Vancouver en una reunión de celebración" loading="lazy">
      </div>
    </div>
    <ul class="comunidad__list">
      <?php foreach ( $items as $item ) : ?>
        <li data-reveal>
          <?php $logo = vv_image_id( $item['logo'] ?? 0 ); ?>
          <a href="<?php print esc_url( ( $item['url'] ?? '' ) ?: '#' ); ?>"<?php if ( $logo ) print ' class="has-logo"'; ?>>
            <?php if ( $logo ) : ?>
              <span class="comunidad__logo"><?php print wp_get_attachment_image( $logo, 'medium_large', false, [ 'alt' => '', 'loading' => 'lazy', 'aria-hidden' => 'true' ] ); ?></span>
              <span class="comunidad__name screen-reader-text"><?php print esc_html( $item['title'] ?? '' ); ?></span>
            <?php else : ?>
              <span class="comunidad__name"><?php print esc_html( $item['title'] ?? '' ); ?></span>
            <?php endif; ?>
            <span class="comunidad__tag"><?php print esc_html( $item['tag'] ?? '' ); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
