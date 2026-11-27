<?php

/**
 * Cabecera de página interna: título enorme, barras de luz y una foto con corte diagonal.
 */

$fields = get_fields() ?: [];

$classes = [ 'wp-block-vv-page-hero', 'alignfull' ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = !empty( $fields['eyebrow'] ) ? $fields['eyebrow'] : 'Verbo Vancouver';
$heading = !empty( $fields['heading'] ) ? $fields['heading'] : get_the_title();
$text    = $fields['text'] ?? '';
$img     = !empty( $fields['image'] ) ? wp_get_attachment_image_url( $fields['image'], 'large' ) : vv_theme_image( !empty( $fields['image_file'] ) ? $fields['image_file'] : 'home-adoracion.jpg' );
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>">
  <div class="page-hero__lights" aria-hidden="true">
    <?php for ( $i = 0; $i < 9; $i++ ) : ?><span style="--i:<?php print $i; ?>"></span><?php endfor; ?>
  </div>
  <div class="page-hero__inner vv-wrap">
    <div class="page-hero__copy">
      <p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p>
      <h1 class="page-hero__title" data-reveal="split"><?php print vv_highlight( $heading ); ?></h1>
      <?php if ( $text ) : ?><p class="page-hero__text" data-reveal><?php print esc_html( $text ); ?></p><?php endif; ?>
    </div>
    <?php if ( $img ) : ?>
      <figure class="page-hero__media" data-reveal="mask"><img src="<?php print esc_url( $img ); ?>" alt="" fetchpriority="high"></figure>
    <?php endif; ?>
  </div>
</section>
