<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_site_header',
    'title'    => 'Cabecera del sitio',
    'fields'   => array(
      array(
        'key'          => 'field_vv_header_links',
        'label'        => 'Menú principal',
        'name'         => 'links',
        'type'         => 'repeater',
        'layout'       => 'table',
        'max'          => 7,
        'button_label' => 'Añadir enlace',
        'instructions' => 'Si lo dejas vacío se usa el menú por defecto.',
        'sub_fields'   => array(
          array( 'key' => 'field_vv_header_link', 'label' => 'Enlace', 'name' => 'link', 'type' => 'link', 'return_format' => 'array' ),
        ),
      ),
      array( 'key' => 'field_vv_header_cta', 'label' => 'Botón principal', 'name' => 'cta', 'type' => 'link', 'return_format' => 'array' ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/site-header' ) ) ),
    'active'   => true,
  ) );

endif;
