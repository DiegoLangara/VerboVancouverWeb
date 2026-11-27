<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_equipo',
    'title'    => 'Equipo con biografías',
    'fields'   => array(
      array( 'key' => 'field_vv_equipo_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text' ),
      array( 'key' => 'field_vv_equipo_heading', 'label' => 'Título', 'name' => 'heading', 'type' => 'text', 'instructions' => 'Escribe una palabra entre *asteriscos* para resaltarla en ámbar.' ),
      array( 'key' => 'field_vv_equipo_intro', 'label' => 'Introducción', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
      array(
        'key' => 'field_vv_equipo_tone', 'label' => 'Fondo', 'name' => 'tone', 'type' => 'select',
        'choices' => array( 'dark' => 'Oscuro (noche)', 'light' => 'Claro (crema)' ), 'default_value' => 'dark',
      ),
      array(
        'key' => 'field_vv_equipo_columns', 'label' => 'Columnas en escritorio', 'name' => 'columns', 'type' => 'select',
        'choices' => array( '2' => '2', '3' => '3', '4' => '4' ), 'default_value' => '3',
      ),
      array(
        'key'          => 'field_vv_equipo_people',
        'label'        => 'Personas',
        'name'         => 'people',
        'type'         => 'repeater',
        'layout'       => 'block',
        'collapsed'    => 'field_vv_equipo_person_name',
        'button_label' => 'Añadir persona',
        'sub_fields'   => array(
          array( 'key' => 'field_vv_equipo_person_image', 'label' => 'Foto', 'name' => 'image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all', 'wrapper' => array( 'width' => '25' ) ),
          array( 'key' => 'field_vv_equipo_person_name', 'label' => 'Nombre', 'name' => 'name', 'type' => 'text', 'wrapper' => array( 'width' => '40' ) ),
          array( 'key' => 'field_vv_equipo_person_role', 'label' => 'Rol', 'name' => 'role', 'type' => 'text', 'wrapper' => array( 'width' => '35' ) ),
          array( 'key' => 'field_vv_equipo_person_bio', 'label' => 'Biografía o descripción', 'name' => 'bio', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0, 'instructions' => 'Se muestra en una ventana al hacer clic en la foto. Si la dejas vacía, la tarjeta no se puede abrir.' ),
        ),
      ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/equipo' ) ) ),
    'active'   => true,
  ) );

endif;
