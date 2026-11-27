<?php
/**
 * Agrega a la página Nosotros la sección del pastor (bloque Perfil destacado)
 * y la del consejo de la iglesia (bloque Equipo con biografías).
 *
 * Uso, solo en el entorno de desarrollo:
 *   wp eval-file tools/nosotros-pastor-consejo.php --user=<administrador>
 *
 * - Respalda el contenido anterior en ~/backup-pagina-nosotros-<fecha>.html.
 * - Si la página ya tiene un bloque perfil o equipo, no hace nada (se puede correr dos veces sin duplicar).
 * - Los textos de las biografías son provisionales: se editan desde el editor de la página.
 */

if ( !defined( 'ABSPATH' ) || !function_exists( 'acf_get_fields' ) ) {
  fwrite( STDERR, "Ejecuta con wp eval-file y con ACF Pro activo.\n" );
  return;
}

$page = get_page_by_path( 'nosotros' );
if ( !$page ) { WP_CLI::error( 'No encontré la página Nosotros.' ); }

if ( false !== strpos( $page->post_content, 'wp:verbovancouver/perfil' ) || false !== strpos( $page->post_content, 'wp:verbovancouver/equipo' ) ) {
  WP_CLI::log( 'Nosotros ya tiene las secciones del pastor o del consejo; no cambio nada.' );
  return;
}

/** Bloque ACF con sus datos, igual que en crear-paginas.php. */
function vv_np_block( $name, $group, $values, $attrs = [] ) {
  $fields = [];
  foreach ( (array) acf_get_fields( $group ) as $f ) $fields[ $f['name'] ] = $f;
  $data = [];
  foreach ( $values as $key => $value ) {
    if ( empty( $fields[ $key ] ) ) { WP_CLI::warning( "Campo desconocido $key en $group" ); continue; }
    $field = $fields[ $key ];
    if ( 'repeater' === $field['type'] ) {
      $subs = [];
      foreach ( $field['sub_fields'] as $s ) $subs[ $s['name'] ] = $s['key'];
      foreach ( array_values( $value ) as $i => $row ) {
        foreach ( $row as $sk => $sv ) {
          if ( empty( $subs[ $sk ] ) ) continue;
          $data[ "{$key}_{$i}_{$sk}" ]  = $sv;
          $data[ "_{$key}_{$i}_{$sk}" ] = $subs[ $sk ];
        }
      }
      $data[ $key ] = count( $value );
    } else {
      $data[ $key ] = $value;
    }
    $data[ '_' . $key ] = $field['key'];
  }
  return [
    'blockName'    => $name,
    'attrs'        => array_merge( [ 'name' => $name, 'data' => $data, 'mode' => 'preview', 'align' => 'full' ], $attrs ),
    'innerBlocks'  => [],
    'innerHTML'    => '',
    'innerContent' => [],
  ];
}

$pastor_photo = attachment_url_to_postid( home_url( '/wp-content/uploads/2026/11/648794356_26019602977704390_8140658063586314938_n.jpg' ) );
if ( !$pastor_photo ) WP_CLI::warning( 'No encontré la foto del pastor en la galería; la sección queda sin foto.' );
else update_post_meta( $pastor_photo, '_wp_attachment_image_alt', 'Pastor Antonio Torres' );

$pastor_bio = '<p>Antonio Torres es el pastor principal de Verbo Vancouver.</p>'
  . '<p>[Texto provisional: aquí va su trayectoria: cómo conoció a Jesús, su llamado al ministerio, su formación y los años que lleva sirviendo en la iglesia. Edítalo desde el bloque «Perfil destacado».]</p>';

$perfil = vv_np_block( 'verbovancouver/perfil', 'group_vv_perfil', [
  'eyebrow' => 'Conoce a nuestro pastor',
  'name'    => 'Antonio *Torres*',
  'role'    => 'Pastor principal',
  'image'   => $pastor_photo ?: '',
  'bio'     => $pastor_bio,
  'quote'   => '',
  'tone'    => 'light',
  'side'    => 'left',
], [ 'anchor' => 'pastor' ] );

$equipo = vv_np_block( 'verbovancouver/equipo', 'group_vv_equipo', [
  'eyebrow' => 'Consejo de la iglesia',
  'heading' => 'Quienes nos *acompañan*',
  'intro'   => 'El consejo apoya al pastor en el cuidado y la dirección de la iglesia. Haz clic en cada persona para conocerla.',
  'tone'    => 'dark',
  'columns' => '3',
  'people'  => [
    [ 'image' => $pastor_photo ?: '', 'name' => 'Antonio Torres', 'role' => 'Pastor principal', 'bio' => $pastor_bio ],
    [ 'image' => '', 'name' => 'Gabriela Torres', 'role' => 'Consejo', 'bio' => '<p>[Texto provisional: biografía corta de Gabriela Torres y su servicio en la iglesia.]</p>' ],
    [ 'image' => '', 'name' => 'Francisco Ayala', 'role' => 'Consejo', 'bio' => '<p>[Texto provisional: biografía corta de Francisco Ayala y su servicio en la iglesia.]</p>' ],
  ],
], [ 'anchor' => 'consejo' ] );

// Orden: encabezado, visión, pastor, resto de secciones, consejo y, al final, "pasos".
$blocks     = parse_blocks( $page->post_content );
$out        = [];
$seen_luz   = 0;
$put_perfil = false;
$put_equipo = false;
$sep        = [ 'blockName' => null, 'attrs' => [], 'innerBlocks' => [], 'innerHTML' => "\n\n", 'innerContent' => [ "\n\n" ] ];

foreach ( $blocks as $b ) {
  if ( !$put_equipo && 'verbovancouver/pasos' === $b['blockName'] ) {
    $out[] = $equipo; $out[] = $sep; $put_equipo = true;
  }
  $out[] = $b;
  if ( 'verbovancouver/seccion-luz' === $b['blockName'] && 1 === ++$seen_luz ) {
    $out[] = $sep; $out[] = $perfil; $put_perfil = true;
  }
}
if ( !$put_perfil ) { $out[] = $sep; $out[] = $perfil; }
if ( !$put_equipo ) { $out[] = $sep; $out[] = $equipo; }

$backup = rtrim( getenv( 'HOME' ) ?: sys_get_temp_dir(), '/' ) . '/backup-pagina-nosotros-' . gmdate( 'Ymd-His' ) . '.html';
file_put_contents( $backup, $page->post_content );

$result = wp_update_post( [ 'ID' => $page->ID, 'post_content' => wp_slash( serialize_blocks( $out ) ) ], true );
if ( is_wp_error( $result ) ) WP_CLI::error( $result->get_error_message() );

WP_CLI::success( "Nosotros actualizada (pastor y consejo). Respaldo: $backup" );
