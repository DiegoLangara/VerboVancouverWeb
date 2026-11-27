<?php

/**
 * Sección con puntos: título, introducción y una lista numerada (con línea de luz) o tarjetas.
 */

$fields = get_fields() ?: [];

$layout  = ( $fields['layout'] ?? 'numbered' ) === 'cards' ? 'cards' : 'numbered';
$classes = [ 'wp-block-vv-seccion-luz', 'alignfull', 'is-' . $layout ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = $fields['eyebrow'] ?? '';
$heading = $fields['heading'] ?? 'Título de la sección';
$intro   = $fields['intro'] ?? '';
$items   = !empty( $fields['items'] ) ? $fields['items'] : [
  [ 'title' => 'Primer punto', 'text' => 'Describe aquí el primer punto.', 'meta' => '' ],
  [ 'title' => 'Segundo punto', 'text' => 'Describe aquí el segundo punto.', 'meta' => '' ],
];
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>">
  <div class="seccion-luz__inner vv-wrap">
    <header class="seccion-luz__head">
      <?php if ( $eyebrow ) : ?><p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p><?php endif; ?>
      <h2 class="vv-title" data-reveal="split"><?php print vv_highlight( $heading ); ?></h2>
      <?php if ( $intro ) : ?><p class="seccion-luz__intro" data-reveal><?php print esc_html( $intro ); ?></p><?php endif; ?>
    </header>

    <ol class="seccion-luz__items">
      <?php foreach ( $items as $i => $item ) :
        $tag = !empty( $item['url'] ) ? 'a' : 'div'; ?>
        <li class="seccion-luz__item"<?php if ( !empty( $item['anchor'] ) ) print ' id="' . esc_attr( $item['anchor'] ) . '"'; ?> data-reveal>
          <<?php print $tag; ?> class="seccion-luz__card"<?php if ( 'a' === $tag ) print ' href="' . esc_url( $item['url'] ) . '"'; ?>>
            <span class="seccion-luz__num" aria-hidden="true"><?php printf( '%02d', $i + 1 ); ?></span>
            <span class="seccion-luz__body">
              <span class="seccion-luz__title"><?php print esc_html( $item['title'] ?? '' ); ?></span>
              <?php if ( !empty( $item['text'] ) ) : ?><span class="seccion-luz__text"><?php print nl2br( esc_html( $item['text'] ) ); ?></span><?php endif; ?>
              <?php if ( !empty( $item['meta'] ) ) : ?><span class="seccion-luz__meta"><?php print esc_html( $item['meta'] ); ?></span><?php endif; ?>
            </span>
            <?php if ( 'a' === $tag ) : ?><span class="seccion-luz__arrow" aria-hidden="true">→</span><?php endif; ?>
          </<?php print $tag; ?>>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
