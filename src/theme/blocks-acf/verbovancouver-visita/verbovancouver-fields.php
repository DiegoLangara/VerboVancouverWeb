<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_visita',
    'title'    => 'Planea tu visita',
    'fields'   => array(
      array( 'key' => 'field_vv_visita_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Planea tu visita' ),
      array( 'key' => 'field_vv_visita_heading', 'label' => 'Título', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Te esperamos el sábado' ),
      array( 'key' => 'field_vv_visita_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
      array( 'key' => 'field_vv_visita_day', 'label' => 'Día del servicio (0 = domingo, 6 = sábado)', 'name' => 'day', 'type' => 'number', 'default_value' => 6, 'min' => 0, 'max' => 6, 'wrapper' => array( 'width' => '33' ) ),
      array( 'key' => 'field_vv_visita_time', 'label' => 'Hora (24 h)', 'name' => 'time', 'type' => 'text', 'default_value' => '18:00', 'wrapper' => array( 'width' => '33' ) ),
      array( 'key' => 'field_vv_visita_address', 'label' => 'Dirección', 'name' => 'address', 'type' => 'text', 'default_value' => '7200 Cariboo Road, Burnaby BC', 'wrapper' => array( 'width' => '34' ) ),
      array( 'key' => 'field_vv_visita_image', 'label' => 'Imagen', 'name' => 'image', 'type' => 'image', 'return_format' => 'id' ),
      array( 'key' => 'field_vv_visita_whatsapp', 'label' => 'WhatsApp (solo números, con código de país)', 'name' => 'whatsapp', 'type' => 'text' ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/visita' ) ) ),
    'active'   => true,
  ) );

endif;
