<?php

/**
 * Perfil destacado: foto grande y trayectoria de una persona (por ejemplo, el pastor).
 */

$fields = get_fields();

$tone = ( $fields['tone'] ?? 'light' ) === 'dark' ? 'dark' : 'light';
$side = ( $fields['side'] ?? 'left' ) === 'right' ? 'right' : 'left';

$classes = [ 'wp-block-vv-perfil', 'alignfull', 'is-' . $tone, 'is-photo-' . $side ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = $fields['eyebrow'] ?? 'Nuestro pastor';
$name    = $fields['name'] ?? 'Nombre del pastor';
$role    = $fields['role'] ?? '';
$bio     = $fields['bio'] ?? '';
$quote   = $fields['quote'] ?? '';
$img_id  = !empty( $fields['image'] ) ? ( is_array( $fields['image'] ) ? ( $fields['image']['ID'] ?? 0 ) : (int) $fields['image'] ) : 0;
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>">
  <div class="perfil__grid vv-wrap">
    <figure class="perfil__media vv-zoom" data-reveal="scale">
      <?php if ( $img_id ) :
        print wp_get_attachment_image( $img_id, 'large', false, [ 'alt' => wp_strip_all_tags( str_replace( '*', '', $name ) ), 'loading' => 'lazy' ] );
      else : ?>
        <span class="perfil__placeholder" aria-hidden="true"></span>
      <?php endif; ?>
    </figure>
    <div class="perfil__body">
      <p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p>
      <h2 class="vv-title perfil__name" data-reveal="split"><?php print vv_highlight( $name ); ?></h2>
      <?php if ( $role ) : ?><p class="perfil__role" data-reveal><?php print esc_html( $role ); ?></p><?php endif; ?>
      <?php if ( $quote ) : ?><blockquote class="perfil__quote" data-reveal><p><?php print esc_html( $quote ); ?></p></blockquote><?php endif; ?>
      <?php if ( $bio ) : ?><div class="perfil__bio" data-reveal><?php print wp_kses_post( $bio ); ?></div><?php endif; ?>
    </div>
  </div>
</section>
