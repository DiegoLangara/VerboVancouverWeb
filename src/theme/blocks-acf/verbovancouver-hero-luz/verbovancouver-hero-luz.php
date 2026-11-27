<?php

/**
 * Hero "Luz que habita": barras de luz del escenario que se encienden y forman la cruz.
 * La animación vive en verbovancouver-hero-luz.js (GSAP + ScrollTrigger).
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$fields = get_fields();

$classes = [ 'wp-block-vv-' . substr( $block['name'], 15 ), 'alignfull' ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow   = $fields['eyebrow'] ?? 'Iglesia Cristiana Verbo Vancouver';
$heading   = $fields['heading'] ?? 'Bienvenidos a la familia';
$text      = $fields['text'] ?? 'Un hogar espiritual para latinos en Vancouver. Sábados 6:00 pm.';
$primary   = !empty( $fields['cta_primary'] ) ? $fields['cta_primary'] : [ 'url' => '/#visitanos', 'title' => 'Planea tu visita' ];
$secondary = !empty( $fields['cta_secondary'] ) ? $fields['cta_secondary'] : [ 'url' => '#ultima-predica', 'title' => 'Ver la última prédica' ];
$bars      = max( 5, min( 21, (int) ( $fields['bars'] ?? 13 ) ) );
if ( 0 === $bars % 2 ) $bars++;
$center    = intdiv( $bars, 2 );
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>" data-hero-luz>
  <div class="hero-luz__lights" aria-hidden="true">
    <?php for ( $i = 0; $i < $bars; $i++ ) : ?>
      <span class="hero-luz__bar<?php print $i === $center ? ' hero-luz__bar--cross' : ''; ?>"></span>
    <?php endfor; ?>
    <span class="hero-luz__beam"></span>
  </div>
  <div class="hero-luz__content">
    <?php if ( $eyebrow ) : ?><p class="hero-luz__eyebrow skip-observing"><?php print esc_html( $eyebrow ); ?></p><?php endif; ?>
    <?php if ( $heading ) : ?><h1 class="hero-luz__heading skip-observing"><?php print esc_html( $heading ); ?></h1><?php endif; ?>
    <?php if ( $text ) : ?><p class="hero-luz__text skip-observing"><?php print esc_html( $text ); ?></p><?php endif; ?>
    <?php if ( $primary || $secondary ) : ?>
      <div class="hero-luz__actions">
        <?php foreach ( [ 'primary' => $primary, 'secondary' => $secondary ] as $type => $cta ) :
          if ( empty( $cta['url'] ) ) continue; ?>
          <a class="hero-luz__btn hero-luz__btn--<?php print $type; ?>" href="<?php print esc_url( $cta['url'] ); ?>"<?php if ( !empty( $cta['target'] ) ) print ' target="' . esc_attr( $cta['target'] ) . '" rel="noopener"'; ?>><?php print esc_html( $cta['title'] ); ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <span class="hero-luz__scroll" aria-hidden="true">Desliza</span>
</section>
