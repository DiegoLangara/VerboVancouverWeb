<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_pasos',
    'title'    => 'Siguiente paso',
    'fields'   => array(
      array( 'key' => 'field_vv_pasos_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Tu siguiente paso' ),
      array( 'key' => 'field_vv_pasos_heading', 'label' => 'Título', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Bienvenidos a la familia' ),
      array(
        'key'          => 'field_vv_pasos_items',
        'label'        => 'Pasos',
        'name'         => 'items',
        'type'         => 'repeater',
        'layout'       => 'block',
        'max'          => 3,
        'button_label' => 'Añadir paso',
        'sub_fields'   => array(
          array( 'key' => 'field_vv_pasos_item_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text' ),
          array( 'key' => 'field_vv_pasos_item_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'text' ),
          array( 'key' => 'field_vv_pasos_item_link', 'label' => 'Enlace', 'name' => 'link', 'type' => 'link', 'return_format' => 'array' ),
        ),
      ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/pasos' ) ) ),
    'active'   => true,
  ) );

endif;
