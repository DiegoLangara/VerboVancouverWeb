<?php
/**
 * Crea (o actualiza) las páginas del menú con los bloques "Luz que habita".
 *
 * Uso, solo en el entorno de desarrollo:
 *   wp eval-file tools/crear-paginas.php --user=<administrador>
 *
 * Si una página ya existe, guarda su contenido anterior en ~/backup-pagina-<slug>-<fecha>.html antes de reemplazarlo.
 */

if ( !defined( 'ABSPATH' ) || !function_exists( 'acf_get_fields' ) ) {
  fwrite( STDERR, "Ejecuta con wp eval-file y con ACF Pro activo.\n" );
  return;
}

$church = function_exists( 'vv_church_info' ) ? vv_church_info() : [];

/** Mapa nombre => campo de un grupo ACF local. */
function vv_tool_fields( $group ) {
  $out = [];
  foreach ( (array) acf_get_fields( $group ) as $f ) $out[ $f['name'] ] = $f;
  return $out;
}

/** Bloque ACF con sus datos guardados en el comentario, como lo hace el editor. */
function vv_tool_block( $name, $group = null, $values = [], $attrs = [] ) {
  $data = [];
  if ( $group ) {
    $fields = vv_tool_fields( $group );
    foreach ( $values as $key => $value ) {
      if ( empty( $fields[ $key ] ) ) { fwrite( STDERR, "Campo desconocido $key en $group\n" ); continue; }
      $field = $fields[ $key ];
      if ( 'repeater' === $field['type'] ) {
        $subs = [];
        foreach ( $field['sub_fields'] as $s ) $subs[ $s['name'] ] = $s['key'];
        $data[ $key ] = count( $value );
        $data[ '_' . $key ] = $field['key'];
        foreach ( array_values( $value ) as $i => $row ) {
          foreach ( $row as $sk => $sv ) {
            if ( empty( $subs[ $sk ] ) ) continue;
            $data[ "{$key}_{$i}_{$sk}" ] = $sv;
            $data[ "_{$key}_{$i}_{$sk}" ] = $subs[ $sk ];
          }
        }
      } else {
        $data[ $key ] = $value;
        $data[ '_' . $key ] = $field['key'];
      }
    }
  }
  $json = array_merge( [ 'name' => $name, 'data' => (object) $data, 'mode' => 'preview', 'align' => 'full' ], $attrs );
  return '<!-- wp:' . $name . ' ' . wp_json_encode( $json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . ' /-->';
}

function vv_tool_hero( $eyebrow, $heading, $text, $image ) {
  return vv_tool_block( 'verbovancouver/page-hero', 'group_vv_page_hero', [ 'eyebrow' => $eyebrow, 'heading' => $heading, 'text' => $text, 'image_file' => $image ] );
}

function vv_tool_section( $eyebrow, $heading, $intro, $items, $layout = 'numbered', $attrs = [] ) {
  return vv_tool_block( 'verbovancouver/seccion-luz', 'group_vv_seccion_luz', [ 'eyebrow' => $eyebrow, 'heading' => $heading, 'intro' => $intro, 'layout' => $layout, 'items' => $items ], $attrs );
}

$tel   = 'tel:' . preg_replace( '/[^0-9+]/', '', $church['phone'] ?? '' );
$pasos = vv_tool_block( 'verbovancouver/pasos' );

$pages = [

  'nosotros' => [
    'title'   => 'Nosotros',
    'content' => [
      vv_tool_hero( 'Nosotros', 'Existimos para *glorificar* a Dios', 'Somos una iglesia cristiana en español en Burnaby. Nuestro propósito es glorificar a Dios en todo lo que hacemos, en una relación personal con Él por medio de su hijo Jesús.', 'home-adoracion.jpg' ),
      vv_tool_section( 'Nuestra visión', 'Así vivimos ese *propósito*', 'La visión es la forma en que alcanzamos nuestro propósito. La vivimos en tres aspectos.', [
        [ 'title' => 'Transformados a la imagen de Jesús', 'text' => 'El deseo de Dios es que seamos conformes a la imagen de su hijo. No os conforméis a este siglo, sino transformaos por medio de la renovación de vuestro entendimiento.', 'meta' => 'Romanos 12:2 · Romanos 8:29' ],
        [ 'title' => 'Unidos como familia de Dios', 'text' => 'Buscamos la unidad del cuerpo de Cristo, manteniendo una relación de amor fraternal como la familia de Dios en todo el mundo.', 'meta' => 'Efesios 4:13' ],
        [ 'title' => 'Haciendo discípulos', 'text' => 'Predicamos y enseñamos el evangelio de Jesús a todas las naciones: un mensaje de salvación, amor y perdón.', 'meta' => 'Mateo 28:19' ],
      ] ),
      vv_tool_section( 'Familia Verbo', 'Parte de algo *más grande*', 'Verbo Vancouver forma parte de la familia de iglesias Verbo en Canadá y en el mundo.', [
        [ 'title' => 'Verbo Toronto', 'meta' => 'Ontario' ],
        [ 'title' => 'Verbo Toronto en inglés', 'meta' => 'Ontario' ],
        [ 'title' => 'Verbo Mississauga', 'meta' => 'Ontario' ],
        [ 'title' => 'Verbo Montreal', 'meta' => 'Quebec' ],
      ], 'cards', [ 'className' => 'is-alt' ] ),
      $pasos,
    ],
  ],

  'predicas' => [
    'title'   => 'Prédicas',
    'content' => [
      vv_tool_hero( 'Prédicas', 'La *Palabra* para tu semana', 'Mira la prédica más reciente y todas las anteriores en nuestro canal de YouTube.', 'home-crecimiento.jpg' ),
      vv_tool_block( 'verbovancouver/predica', null, [], [ 'anchor' => 'ultima-predica' ] ),
      vv_tool_section( 'Sigue creciendo', 'Más formas de *escuchar*', '', [
        [ 'title' => 'Canal de YouTube', 'text' => 'Todas las prédicas y las transmisiones de los sábados.', 'url' => $church['social']['youtube'] ?? '' ],
        [ 'title' => 'Reunión en vivo', 'text' => 'Sábados a las 6:00 pm en 7200 Cariboo Rd, Burnaby.', 'url' => '/#visitanos' ],
        [ 'title' => 'Grupos en casa', 'text' => 'Conversa la prédica de la semana con un grupo cerca de ti.', 'url' => '/igho/' ],
      ], 'cards' ),
      $pasos,
    ],
  ],

  'ministerios' => [
    'title'   => 'Ministerios',
    'content' => [
      vv_tool_hero( 'Ministerios', 'Hay un lugar para *ti*', 'Desde los más pequeños hasta los adultos, cada ministerio es una forma de crecer, servir y caminar juntos.', 'home-comunidad.jpg' ),
      vv_tool_section( 'Para cada etapa', 'Crecemos *juntos*', 'Conoce los ministerios de Verbo Vancouver y encuentra dónde conectar o servir.', [
        [ 'anchor' => 'kids', 'title' => 'Verbo Kids', 'text' => 'Enseñamos a cada niño los principios y verdades de la Palabra de Dios, para que conozcan a Jesús y vivan una vida abundante, llena de gozo.' ],
        [ 'anchor' => 'youth', 'title' => 'Verbo Youth', 'text' => 'Jóvenes con energía y pasión por la presencia de Dios: comprometidos con Cristo, creciendo en Él y compartiéndolo con el mundo.' ],
        [ 'anchor' => 'alabanza', 'title' => 'Alabanza', 'text' => 'Glorificamos a Dios con música y canciones que nos llevan a confesar su nombre y a experimentar su presencia en cada reunión.' ],
        [ 'anchor' => 'danza', 'title' => 'Danza', 'text' => 'Niñas, jóvenes y mujeres expresan su adoración a Dios con panderos, listones y banderas.' ],
        [ 'anchor' => 'hombres', 'title' => 'Hombres', 'text' => 'Hombres conforme al corazón de Dios: cuidamos el pacto matrimonial y amamos a nuestras esposas como Cristo amó a la iglesia.' ],
        [ 'anchor' => 'mujeres', 'title' => 'Mujeres', 'text' => 'Una celebración a Dios con enseñanza práctica sobre la familia, los hijos y el crecimiento espiritual. Y siempre un cafecito.', 'meta' => 'Una reunión al mes' ],
        [ 'anchor' => 'matrimonios', 'title' => 'Matrimonios', 'text' => 'Equipamos a las parejas con los fundamentos bíblicos del matrimonio para disfrutar de una relación que crece cada día.', 'meta' => 'Efesios 5:33' ],
        [ 'anchor' => 'oracion', 'title' => 'Oración', 'text' => 'La oración es hablar con Dios y también escucharle. Oramos juntos por la iglesia, las familias y la ciudad.' ],
        [ 'anchor' => 'ujieres', 'title' => 'Ujieres', 'text' => 'Te recibimos con una sonrisa, te ayudamos a encontrar un asiento y estamos atentos para orar contigo por cualquier necesidad.' ],
      ], 'cards' ),
      $pasos,
    ],
  ],

  'eventos' => [
    'title'   => 'Eventos',
    'content' => [
      vv_tool_hero( 'Eventos', 'Lo que viene en *Verbo*', 'Reuniones, encuentros y fechas especiales. Muy pronto verás aquí el calendario completo y podrás inscribirte en línea.', 'home-visita.jpg' ),
      vv_tool_section( 'Cada semana', 'Nuestro *ritmo*', 'Estas son las reuniones que se repiten durante el mes. Las fechas especiales se anunciarán aquí y en nuestras redes.', [
        [ 'title' => 'Reunión general', 'text' => 'Adoración, Palabra y familia, con espacio para los niños.', 'meta' => 'Sábados · 6:00 pm · 7200 Cariboo Rd', 'url' => '/#visitanos' ],
        [ 'title' => 'Grupos en casa (IGHO)', 'text' => 'Durante la semana nos reunimos en hogares de distintas zonas de la ciudad.', 'meta' => 'Entre semana', 'url' => '/igho/' ],
        [ 'title' => 'Reunión de mujeres', 'text' => 'Una celebración a Dios con enseñanza práctica y un cafecito.', 'meta' => 'Una vez al mes', 'url' => '/ministerios/#mujeres' ],
      ] ),
      vv_tool_block( 'verbovancouver/visita' ),
    ],
  ],

  'igho' => [
    'title'   => 'Grupos en casa',
    'content' => [
      vv_tool_hero( 'Grupos en casa · IGHO', 'Iglesia en el *hogar*', 'Las IGHO son grupos pequeños que se reúnen en casas durante la semana para orar, estudiar la Palabra y cuidarnos unos a otros.', 'home-familia.jpg' ),
      vv_tool_section( 'Cómo funciona', 'Nadie camina *solo*', '', [
        [ 'title' => 'Encuentra un grupo', 'text' => 'Escríbenos y te conectamos con una IGHO cerca de tu casa.' ],
        [ 'title' => 'Comparte la mesa', 'text' => 'Oramos, conversamos la Palabra y nos conocemos de verdad.' ],
        [ 'title' => 'Crece y sirve', 'text' => 'Del grupo nacen amistades, apoyo en los tiempos difíciles y nuevas formas de servir.' ],
      ] ),
      vv_tool_section( '¿Quieres unirte?', 'Te *conectamos*', 'Cuéntanos en qué zona vives y te ponemos en contacto con un grupo.', [
        [ 'title' => 'Llámanos', 'text' => $church['phone'] ?? '', 'url' => $tel ],
        [ 'title' => 'Escríbenos', 'text' => $church['email'] ?? '', 'url' => 'mailto:' . ( $church['email'] ?? '' ) ],
      ], 'cards', [ 'className' => 'is-alt' ] ),
      $pasos,
    ],
  ],

  'contacto' => [
    'title'   => 'Contacto',
    'content' => [
      vv_tool_hero( 'Contacto', 'Estamos para *servirte*', 'Escríbenos, llámanos o visítanos un sábado. Nos encantará conocerte.', 'home-edificio.jpg' ),
      vv_tool_section( 'Escríbenos', '*Hablemos*', 'Respondemos tan pronto como podamos.', [
        [ 'title' => 'Teléfono', 'text' => $church['phone'] ?? '', 'url' => $tel ],
        [ 'title' => 'Correo', 'text' => $church['email'] ?? '', 'url' => 'mailto:' . ( $church['email'] ?? '' ) ],
        [ 'title' => 'Dirección', 'text' => $church['address'] ?? '', 'meta' => $church['service'] ?? '', 'url' => $church['map_url'] ?? '' ],
        [ 'title' => 'Dar en línea', 'text' => 'Sé parte de lo que Dios hace en Vancouver.', 'url' => $church['give_url'] ?? '' ],
      ], 'cards' ),
      vv_tool_block( 'verbovancouver/visita', null, [], [ 'anchor' => 'visitanos' ] ),
    ],
  ],
];

$stamp = gmdate( 'Ymd-His' );
$home  = getenv( 'HOME' ) ?: sys_get_temp_dir();

foreach ( $pages as $slug => $page ) {
  $content  = implode( "\n\n", $page['content'] );
  $existing = get_page_by_path( $slug, OBJECT, 'page' );

  if ( $existing ) {
    $file = "$home/backup-pagina-$slug-$stamp.html";
    file_put_contents( $file, $existing->post_content );
    $id = wp_update_post( [ 'ID' => $existing->ID, 'post_title' => $page['title'], 'post_content' => wp_slash( $content ), 'post_status' => 'publish' ], true );
    $msg = "actualizada (respaldo en $file)";
  } else {
    $id = wp_insert_post( [ 'post_type' => 'page', 'post_name' => $slug, 'post_title' => $page['title'], 'post_content' => wp_slash( $content ), 'post_status' => 'publish' ], true );
    $msg = 'creada';
  }

  if ( is_wp_error( $id ) ) WP_CLI::warning( "$slug: " . $id->get_error_message() );
  else WP_CLI::success( "/$slug/ $msg (ID $id)" );
}
