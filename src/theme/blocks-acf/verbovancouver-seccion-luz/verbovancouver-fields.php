<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_seccion_luz',
    'title'    => 'Sección con puntos',
    'fields'   => array(
      array( 'key' => 'field_vv_sl_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text' ),
      array( 'key' => 'field_vv_sl_heading', 'label' => 'Título', 'name' => 'heading', 'type' => 'text', 'instructions' => 'Escribe una palabra entre *asteriscos* para resaltarla en ámbar.' ),
      array( 'key' => 'field_vv_sl_intro', 'label' => 'Introducción', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
      array(
        'key' => 'field_vv_sl_layout', 'label' => 'Diseño', 'name' => 'layout', 'type' => 'select',
        'choices' => array( 'numbered' => 'Lista numerada', 'cards' => 'Tarjetas' ), 'default_value' => 'numbered',
      ),
      array(
        'key'          => 'field_vv_sl_items',
        'label'        => 'Puntos',
        'name'         => 'items',
        'type'         => 'repeater',
        'layout'       => 'block',
        'button_label' => 'Añadir punto',
        'sub_fields'   => array(
          array( 'key' => 'field_vv_sl_item_title', 'label' => 'Título', 'name' => 'title', 'type' => 'text' ),
          array( 'key' => 'field_vv_sl_item_logo', 'label' => 'Logo (opcional)', 'name' => 'logo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Con logo, el título se muestra pequeño en ámbar arriba (en lugar del número) y el logo debajo. Usa el logo horizontal en blanco.' ),
          array( 'key' => 'field_vv_sl_item_text', 'label' => 'Texto', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
          array( 'key' => 'field_vv_sl_item_meta', 'label' => 'Detalle (versículo, horario, responsable)', 'name' => 'meta', 'type' => 'text' ),
          array( 'key' => 'field_vv_sl_item_url', 'label' => 'Enlace', 'name' => 'url', 'type' => 'text' ),
          array( 'key' => 'field_vv_sl_item_anchor', 'label' => 'Ancla (id)', 'name' => 'anchor', 'type' => 'text' ),
        ),
      ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/seccion-luz' ) ) ),
    'active'   => true,
  ) );

endif;
