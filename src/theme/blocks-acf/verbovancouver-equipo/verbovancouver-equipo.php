<?php

/**
 * Equipo con biografías: fotos de un grupo de personas (consejo, servidores, alabanza...).
 * Al hacer clic en una persona se abre su biografía en una ventana modal (<dialog>).
 */

$fields = get_fields();

$tone    = ( $fields['tone'] ?? 'dark' ) === 'light' ? 'light' : 'dark';
$columns = in_array( (string) ( $fields['columns'] ?? '3' ), [ '2', '3', '4' ], true ) ? (string) $fields['columns'] : '3';

$classes = [ 'wp-block-vv-equipo', 'alignfull', 'is-' . $tone ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = $fields['eyebrow'] ?? '';
$heading = $fields['heading'] ?? 'Nuestro *equipo*';
$intro   = $fields['intro'] ?? '';
$people  = !empty( $fields['people'] ) ? $fields['people'] : [
  [ 'name' => 'Nombre', 'role' => 'Rol', 'bio' => '', 'image' => 0 ],
];

$uid = 'vv-equipo-' . substr( md5( ( $block['id'] ?? '' ) . wp_json_encode( $people ) ), 0, 8 );

if ( !function_exists( 'vv_equipo_initials' ) ) {
  function vv_equipo_initials( $name ) {
    $out = '';
    foreach ( preg_split( '/\s+/', trim( (string) $name ) ) as $part ) {
      if ( '' !== $part ) $out .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
      if ( mb_strlen( $out ) >= 2 ) break;
    }
    return $out;
  }
}

if ( !function_exists( 'vv_equipo_image_id' ) ) {
  function vv_equipo_image_id( $image ) {
    if ( is_array( $image ) ) return (int) ( $image['ID'] ?? 0 );
    return (int) $image;
  }
}
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>" data-equipo>
  <div class="vv-wrap">
    <?php if ( $eyebrow || $heading || $intro ) : ?>
      <header class="equipo__head">
        <?php if ( $eyebrow ) : ?><p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p><?php endif; ?>
        <?php if ( $heading ) : ?><h2 class="vv-title" data-reveal="split"><?php print vv_highlight( $heading ); ?></h2><?php endif; ?>
        <?php if ( $intro ) : ?><p class="vv-lead" data-reveal><?php print esc_html( $intro ); ?></p><?php endif; ?>
      </header>
    <?php endif; ?>

    <ul class="equipo__grid" style="--equipo-cols: <?php print esc_attr( $columns ); ?>" role="list">
      <?php foreach ( $people as $i => $person ) :
        $name   = $person['name'] ?? '';
        $role   = $person['role'] ?? '';
        $bio    = $person['bio'] ?? '';
        $img_id = vv_equipo_image_id( $person['image'] ?? 0 );
        $dialog = $uid . '-' . $i;
        $photo  = $img_id
          ? wp_get_attachment_image( $img_id, 'large', false, [ 'alt' => '', 'loading' => 'lazy' ] )
          : '<span class="equipo__initials" aria-hidden="true">' . esc_html( vv_equipo_initials( $name ) ) . '</span>';
        $tag    = $bio ? 'button' : 'div'; ?>
        <li class="equipo__item" data-reveal>
          <<?php print $tag; ?> class="equipo__card"<?php if ( $bio ) : ?> type="button" data-equipo-open="<?php print esc_attr( $dialog ); ?>" aria-haspopup="dialog" aria-label="<?php print esc_attr( sprintf( 'Ver biografía de %s', $name ) ); ?>"<?php endif; ?>>
            <span class="equipo__media vv-zoom"><?php print $photo; ?></span>
            <span class="equipo__name"><?php print esc_html( $name ); ?></span>
            <?php if ( $role ) : ?><span class="equipo__role"><?php print esc_html( $role ); ?></span><?php endif; ?>
            <?php if ( $bio ) : ?><span class="equipo__more" aria-hidden="true">Ver biografía</span><?php endif; ?>
          </<?php print $tag; ?>>

          <?php if ( $bio ) : ?>
            <dialog class="equipo__dialog skip-observing" id="<?php print esc_attr( $dialog ); ?>" aria-labelledby="<?php print esc_attr( $dialog ); ?>-title">
              <div class="equipo__dialog-inner">
                <button class="equipo__close" type="button" data-equipo-close aria-label="Cerrar"><span aria-hidden="true">×</span></button>
                <div class="equipo__dialog-media"><?php print $photo; ?></div>
                <div class="equipo__dialog-body">
                  <?php if ( $role ) : ?><p class="vv-eyebrow skip-observing"><?php print esc_html( $role ); ?></p><?php endif; ?>
                  <h3 class="equipo__dialog-name skip-observing" id="<?php print esc_attr( $dialog ); ?>-title"><?php print esc_html( $name ); ?></h3>
                  <div class="equipo__bio skip-observing"><?php print wp_kses_post( $bio ); ?></div>
                </div>
              </div>
            </dialog>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
