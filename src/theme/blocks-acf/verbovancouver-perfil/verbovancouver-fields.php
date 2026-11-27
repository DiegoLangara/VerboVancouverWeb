<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_perfil',
    'title'    => 'Perfil destacado',
    'fields'   => array(
      array( 'key' => 'field_vv_perfil_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Nuestro pastor' ),
      array( 'key' => 'field_vv_perfil_name', 'label' => 'Nombre', 'name' => 'name', 'type' => 'text', 'instructions' => 'Escribe una palabra entre *asteriscos* para resaltarla.' ),
      array( 'key' => 'field_vv_perfil_role', 'label' => 'Rol', 'name' => 'role', 'type' => 'text' ),
      array( 'key' => 'field_vv_perfil_image', 'label' => 'Foto', 'name' => 'image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ),
      array( 'key' => 'field_vv_perfil_bio', 'label' => 'Trayectoria', 'name' => 'bio', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0 ),
      array( 'key' => 'field_vv_perfil_quote', 'label' => 'Frase destacada (opcional)', 'name' => 'quote', 'type' => 'textarea', 'rows' => 2 ),
      array(
        'key' => 'field_vv_perfil_tone', 'label' => 'Fondo', 'name' => 'tone', 'type' => 'select',
        'choices' => array( 'light' => 'Claro (crema)', 'dark' => 'Oscuro (noche)' ), 'default_value' => 'light',
      ),
      array(
        'key' => 'field_vv_perfil_side', 'label' => 'Foto a la', 'name' => 'side', 'type' => 'select',
        'choices' => array( 'left' => 'Izquierda', 'right' => 'Derecha' ), 'default_value' => 'left',
      ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/perfil' ) ) ),
    'active'   => true,
  ) );

endif;
