<?php
/**
 * Rellena los bloques "Luz que habita" con su contenido por defecto, para que al editarlos en wp-admin
 * los campos ya estén llenos, y sube a la Biblioteca de medios las fotos del tema que usan.
 *
 * Por qué: las plantillas PHP de los bloques muestran textos e imágenes de respaldo cuando un campo
 * está vacío, pero ese respaldo no está guardado en el bloque. Por eso el bloque se veía completo
 * y, al editarlo, los campos aparecían vacíos.
 *
 * Uso, solo en el entorno de desarrollo:
 *   wp eval-file tools/rellenar-bloques.php --user=<administrador>
 *   wp eval-file tools/rellenar-bloques.php 5 --user=<administrador>   (solo la página con ID 5)
 *
 * - Nunca pisa un campo que ya tiene valor: solo llena los que faltan o están vacíos.
 * - Un repetidor solo se llena si no tiene filas.
 * - Antes de guardar una página, respalda su contenido en ~/backup-pagina-<id>-<fecha>.html.
 * - Cada foto se importa una sola vez (se marca con el meta _vv_theme_image).
 */

if ( !defined( 'ABSPATH' ) || !function_exists( 'acf_get_fields' ) ) {
  fwrite( STDERR, "Ejecuta con wp eval-file y con ACF Pro activo.\n" );
  return;
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/** ID del adjunto para una foto de assets/images del tema; la importa la primera vez. */
function vv_fill_media( $file ) {
  static $cache = [];
  if ( isset( $cache[ $file ] ) ) return $cache[ $file ];

  $found = get_posts( [ 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_vv_theme_image', 'meta_value' => $file ] );
  if ( $found ) return $cache[ $file ] = (int) $found[0];

  $src = get_template_directory() . '/assets/images/' . $file;
  if ( !file_exists( $src ) ) { WP_CLI::warning( "No existe la imagen del tema $file" ); return $cache[ $file ] = 0; }

  $tmp = wp_tempnam( $file );
  copy( $src, $tmp );
  $id = media_handle_sideload( [ 'name' => $file, 'tmp_name' => $tmp ], 0 );
  if ( is_wp_error( $id ) ) { @unlink( $tmp ); WP_CLI::warning( "No se pudo importar $file: " . $id->get_error_message() ); return $cache[ $file ] = 0; }

  update_post_meta( $id, '_vv_theme_image', $file );
  WP_CLI::log( "Imagen importada: $file (ID $id)" );
  return $cache[ $file ] = (int) $id;
}

function vv_fill_link( $url, $title ) {
  return [ 'title' => $title, 'url' => $url, 'target' => '' ];
}

$church = function_exists( 'vv_church_info' ) ? vv_church_info() : [];

// Lo mismo que muestran las plantillas de los bloques cuando un campo está vacío.
// Las imágenes van como nombre de archivo del tema y se convierten en ID de adjunto.
$defaults = [
  'verbovancouver/hero-luz' => [
    'eyebrow'       => 'Iglesia Cristiana Verbo Vancouver',
    'heading'       => 'Bienvenidos a la familia',
    'text'          => 'Un hogar espiritual para latinos en Vancouver. Sábados 6:00 pm.',
    'cta_primary'   => vv_fill_link( '/#visitanos', 'Planea tu visita' ),
    'cta_secondary' => vv_fill_link( '#ultima-predica', 'Ver la última prédica' ),
    'bars'          => 13,
  ],
  'verbovancouver/pilares' => [
    'eyebrow' => 'Lo que nos mueve',
    'heading' => 'Tres pilares, una familia',
    'items'   => [
      [ 'title' => 'Adoración', 'text' => 'Cultos vibrantes en español. Cada sábado celebramos a Dios con gozo, música y libertad.', 'image' => 'home-adoracion.jpg', 'link' => vv_fill_link( '/eventos/', 'Ver reuniones' ) ],
      [ 'title' => 'Comunidad', 'text' => 'Grupos en casa (IGHO), familias, jóvenes y niños. Nadie camina solo.', 'image' => 'home-comunidad.jpg', 'link' => vv_fill_link( '/igho/', 'Encuentra tu grupo' ) ],
      [ 'title' => 'Crecimiento', 'text' => 'Predicaciones y estudios bíblicos prácticos para la vida diaria.', 'image' => 'home-crecimiento.jpg', 'link' => vv_fill_link( '/predicas/', 'Escuchar prédicas' ) ],
    ],
  ],
  'verbovancouver/visita' => [
    'eyebrow' => 'Planea tu visita',
    'heading' => 'Te esperamos el sábado',
    'text'    => 'Llega unos minutos antes: te recibimos en la entrada, hay estacionamiento gratuito y un espacio seguro para los niños.',
    'day'     => 6,
    'time'    => '18:00',
    'address' => '7200 Cariboo Road, Burnaby BC',
    'image'   => 'home-visita.jpg',
  ],
  'verbovancouver/predica' => [
    'eyebrow' => 'Prédica más reciente',
    'heading' => 'Escucha la Palabra de esta semana',
    'speaker' => 'Reunión de celebración · Sábados 6:00 pm',
    'channel' => 'https://www.youtube.com/channel/UC_vqNqKT2nOjLyubHzqe7Kg',
  ],
  'verbovancouver/comunidad' => [
    'eyebrow' => 'Comunidad',
    'heading' => 'Hay un lugar para ti',
    'text'    => 'Desde los más pequeños hasta los abuelos: crecemos juntos en grupos, ministerios y en las Iglesias en el Hogar (IGHO).',
    'image'   => 'home-familia.jpg',
    'items'   => [
      [ 'title' => 'IGHO', 'tag' => 'Iglesias en el hogar', 'url' => '/igho/' ],
      [ 'title' => 'Verbo Kids', 'tag' => '0 a 11 años', 'url' => '/ministerios/#kids' ],
      [ 'title' => 'Verbo Juniors', 'tag' => '12 a 14 años', 'url' => '/ministerios/#youth' ],
      [ 'title' => 'Verbo Youth', 'tag' => '15 a 19 años', 'url' => '/ministerios/#youth' ],
      [ 'title' => 'Matrimonios', 'tag' => 'Parejas', 'url' => '/ministerios/#matrimonios' ],
      [ 'title' => 'Mujeres y Hombres', 'tag' => 'Adultos', 'url' => '/ministerios/#mujeres' ],
      [ 'title' => 'Alabanza y Danza', 'tag' => 'Servir con talentos', 'url' => '/ministerios/#alabanza' ],
    ],
  ],
  'verbovancouver/pasos' => [
    'eyebrow' => 'Tu siguiente paso',
    'heading' => 'Bienvenidos a la familia',
    'items'   => [
      [ 'title' => 'Visitar', 'text' => 'Ven este sábado a las 6:00 pm.', 'link' => vv_fill_link( '/#visitanos', 'Planear mi visita' ) ],
      [ 'title' => 'Conectar', 'text' => 'Únete a un grupo en casa cerca de ti.', 'link' => vv_fill_link( '/igho/', 'Unirme a un grupo' ) ],
      [ 'title' => 'Dar', 'text' => 'Sé parte de lo que Dios hace en Vancouver.', 'link' => vv_fill_link( $church['give_url'] ?? 'https://tithe.ly/give?c=1647510', 'Donar en línea' ) ],
    ],
  ],
  // En el encabezado de las páginas internas la foto viene de image_file; se sube y se asigna al campo image.
  'verbovancouver/page-hero' => [
    'image' => '@image_file',
  ],
];

/** Campos de un bloque ACF por nombre, con sus subcampos si es repetidor. */
function vv_fill_fields( $block_name ) {
  static $cache = [];
  if ( isset( $cache[ $block_name ] ) ) return $cache[ $block_name ];
  $group = 'group_vv_' . str_replace( '-', '_', substr( $block_name, strlen( 'verbovancouver/' ) ) );
  $out = [];
  foreach ( (array) acf_get_fields( $group ) as $f ) $out[ $f['name'] ] = $f;
  if ( !$out ) WP_CLI::warning( "No encontré el grupo de campos $group" );
  return $cache[ $block_name ] = $out;
}

function vv_fill_is_empty( $data, $key ) {
  return !array_key_exists( $key, $data ) || '' === $data[ $key ] || null === $data[ $key ] || [] === $data[ $key ];
}

/** Convierte nombres de archivo en IDs para los campos de imagen. */
function vv_fill_value( $field, $value, $data ) {
  if ( 'image' === $field['type'] && is_string( $value ) ) {
    if ( '@image_file' === $value ) $value = !empty( $data['image_file'] ) ? $data['image_file'] : 'home-adoracion.jpg';
    return vv_fill_media( $value ) ?: '';
  }
  return $value;
}

function vv_fill_block( array &$block, array $defaults, array &$changes ) {
  if ( !empty( $block['innerBlocks'] ) ) {
    foreach ( $block['innerBlocks'] as &$inner ) vv_fill_block( $inner, $defaults, $changes );
    unset( $inner );
  }
  $name = $block['blockName'] ?? '';
  if ( empty( $defaults[ $name ] ) ) return;

  $fields = vv_fill_fields( $name );
  $data   = isset( $block['attrs']['data'] ) ? (array) $block['attrs']['data'] : [];

  foreach ( $defaults[ $name ] as $key => $value ) {
    if ( empty( $fields[ $key ] ) ) { WP_CLI::warning( "Campo desconocido $key en $name" ); continue; }
    $field = $fields[ $key ];

    if ( 'repeater' === $field['type'] ) {
      if ( !vv_fill_is_empty( $data, $key ) && (int) $data[ $key ] > 0 ) continue;
      $subs = [];
      foreach ( $field['sub_fields'] as $s ) $subs[ $s['name'] ] = $s;
      foreach ( array_values( $value ) as $i => $row ) {
        foreach ( $row as $sk => $sv ) {
          if ( empty( $subs[ $sk ] ) ) continue;
          $data[ "{$key}_{$i}_{$sk}" ]  = vv_fill_value( $subs[ $sk ], $sv, $data );
          $data[ "_{$key}_{$i}_{$sk}" ] = $subs[ $sk ]['key'];
        }
      }
      $data[ $key ]       = count( $value );
      $data[ '_' . $key ] = $field['key'];
      $changes[] = "$name: $key (" . count( $value ) . ' filas)';
      continue;
    }

    if ( !vv_fill_is_empty( $data, $key ) ) continue;
    $filled = vv_fill_value( $field, $value, $data );
    if ( '' === $filled ) continue;
    $data[ $key ]       = $filled;
    $data[ '_' . $key ] = $field['key'];
    $changes[] = "$name: $key";
  }

  $block['attrs']['data'] = $data;
  if ( empty( $block['attrs']['name'] ) ) $block['attrs']['name'] = $name;
  if ( empty( $block['attrs']['mode'] ) ) $block['attrs']['mode'] = 'preview';
}

$ids = array_filter( array_map( 'intval', $args ?? [] ) );
if ( !$ids ) {
  $ids = get_posts( [ 'post_type' => 'page', 'post_status' => [ 'publish', 'draft', 'private', 'pending', 'future' ], 'numberposts' => -1, 'fields' => 'ids' ] );
}

foreach ( $ids as $id ) {
  $post = get_post( $id );
  if ( !$post || false === strpos( $post->post_content, 'wp:verbovancouver/' ) ) continue;

  $blocks  = parse_blocks( $post->post_content );
  $changes = [];
  foreach ( $blocks as &$block ) vv_fill_block( $block, $defaults, $changes );
  unset( $block );

  if ( !$changes ) { WP_CLI::log( "Página $id ({$post->post_name}): nada que rellenar." ); continue; }

  $backup = rtrim( getenv( 'HOME' ) ?: sys_get_temp_dir(), '/' ) . "/backup-pagina-$id-" . gmdate( 'Ymd-His' ) . '.html';
  file_put_contents( $backup, $post->post_content );

  $result = wp_update_post( [ 'ID' => $id, 'post_content' => wp_slash( serialize_blocks( $blocks ) ) ], true );
  if ( is_wp_error( $result ) ) { WP_CLI::warning( "No se pudo guardar la página $id: " . $result->get_error_message() ); continue; }

  WP_CLI::success( "Página $id ({$post->post_name}) rellenada. Respaldo: $backup" );
  foreach ( $changes as $c ) WP_CLI::log( "  · $c" );
}
