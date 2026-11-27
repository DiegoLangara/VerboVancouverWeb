<?php

/**
 * Última prédica: reproductor grande con fachada (la miniatura carga primero, el iframe solo al hacer clic).
 */

$fields = get_fields();

$classes = [ 'wp-block-vv-predica', 'alignfull' ];
if ( !empty( $block['className'] ) ) $classes[] = trim( $block['className'] );
if ( !empty( $is_preview ) ) $classes[] = 'is-preview';

$eyebrow = $fields['eyebrow'] ?? 'Prédica más reciente';
$heading = $fields['heading'] ?? 'Escucha la Palabra de esta semana';
$speaker = $fields['speaker'] ?? 'Reunión de celebración · Sábados 6:00 pm';
$channel = $fields['channel'] ?? 'https://www.youtube.com/channel/UC_vqNqKT2nOjLyubHzqe7Kg';
$video   = $fields['video'] ?? '';
$id      = '';
if ( $video && preg_match( '~(?:youtu\.be/|v=|/embed/|/live/|/shorts/)([A-Za-z0-9_-]{11})~', $video, $m ) ) $id = $m[1];
$poster  = $id ? 'https://i.ytimg.com/vi/' . $id . '/maxresdefault.jpg' : vv_theme_image( 'home-crecimiento.jpg' );
?>
<section <?php if ( !empty( $block['anchor'] ) ) print 'id="' . esc_attr( $block['anchor'] ) . '" '; ?>class="<?php print esc_attr( implode( ' ', $classes ) ); ?>" data-predica>
  <div class="predica__head vv-wrap">
    <div>
      <p class="vv-eyebrow" data-reveal><?php print esc_html( $eyebrow ); ?></p>
      <h2 class="vv-title" data-reveal="split"><?php print esc_html( $heading ); ?></h2>
    </div>
    <a class="vv-btn vv-btn--line" href="<?php print esc_url( $channel ); ?>" target="_blank" rel="noopener" data-reveal>Todas las prédicas</a>
  </div>
  <div class="predica__player vv-wrap" data-reveal="scale">
    <?php if ( $id ) : ?>
      <button class="predica__facade" type="button" data-video="<?php print esc_attr( $id ); ?>" aria-label="<?php print esc_attr( 'Reproducir: ' . $heading ); ?>">
    <?php else : ?>
      <a class="predica__facade" href="<?php print esc_url( $channel ); ?>" target="_blank" rel="noopener" aria-label="Ver el canal de YouTube">
    <?php endif; ?>
        <img src="<?php print esc_url( $poster ); ?>" alt="" loading="lazy">
        <span class="predica__play" aria-hidden="true"></span>
        <span class="predica__meta"><?php print esc_html( $speaker ); ?></span>
    <?php print $id ? '</button>' : '</a>'; ?>
  </div>
</section>
