<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_pilares',
    'title'    => 'Pilares',
    'fields'   => array(
      array( 'key' => 'field_vv_pilares_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Lo que nos mueve' ),
      array( 'key' => 'field_vv_pilares_heading', 'label' => 'Título', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Tres pilares, una familia' ),
      array(
        'key'          => 'field_vv_pilares_items',
        'label'        => 'Pilares',
        'name'         => 'items',
        'type'         => 'repeater',
        'layout'       => 'block',
        'button_label' => 'Añadir pilar',
        'sub_fields'   => array(
          array( 'key' => 'field_vv_pilares_item_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text' ),
          array( 'key' => 'field_vv_pilares_item_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
          array( 'key' => 'field_vv_pilares_item_image', 'label' => 'Imagen', 'name' => 'image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ),
          array( 'key' => 'field_vv_pilares_item_link', 'label' => 'Enlace', 'name' => 'link', 'type' => 'link', 'return_format' => 'array' ),
        ),
      ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/pilares' ) ) ),
    'active'   => true,
  ) );

endif;
