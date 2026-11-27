<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_comunidad',
    'title'    => 'Comunidad',
    'fields'   => array(
      array( 'key' => 'field_vv_comunidad_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Comunidad' ),
      array( 'key' => 'field_vv_comunidad_heading', 'label' => 'Título', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Hay un lugar para ti' ),
      array( 'key' => 'field_vv_comunidad_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
      array( 'key' => 'field_vv_comunidad_image', 'label' => 'Imagen', 'name' => 'image', 'type' => 'image', 'return_format' => 'id' ),
      array(
        'key'          => 'field_vv_comunidad_items',
        'label'        => 'Ministerios',
        'name'         => 'items',
        'type'         => 'repeater',
        'layout'       => 'table',
        'button_label' => 'Añadir ministerio',
        'sub_fields'   => array(
          array( 'key' => 'field_vv_comunidad_item_title', 'label' => 'Nombre', 'name' => 'title', 'type' => 'text' ),
          array( 'key' => 'field_vv_comunidad_item_tag', 'label' => 'Para quién', 'name' => 'tag', 'type' => 'text' ),
          array( 'key' => 'field_vv_comunidad_item_url', 'label' => 'Enlace', 'name' => 'url', 'type' => 'url' ),
        ),
      ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/comunidad' ) ) ),
    'active'   => true,
  ) );

endif;
