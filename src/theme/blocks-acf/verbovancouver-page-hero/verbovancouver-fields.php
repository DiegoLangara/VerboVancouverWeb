<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_page_hero',
    'title'    => 'Cabecera de página',
    'fields'   => array(
      array( 'key' => 'field_vv_ph_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text' ),
      array( 'key' => 'field_vv_ph_heading', 'label' => 'Título', 'name' => 'heading', 'type' => 'text', 'instructions' => 'Escribe una palabra entre *asteriscos* para resaltarla en ámbar.' ),
      array( 'key' => 'field_vv_ph_text', 'label' => 'Introducción', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
      array( 'key' => 'field_vv_ph_image', 'label' => 'Imagen', 'name' => 'image', 'type' => 'image', 'return_format' => 'id' ),
      array( 'key' => 'field_vv_ph_image_file', 'label' => 'Imagen del tema (si no hay imagen)', 'name' => 'image_file', 'type' => 'text', 'default_value' => 'home-adoracion.jpg' ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/page-hero' ) ) ),
    'active'   => true,
  ) );

endif;
