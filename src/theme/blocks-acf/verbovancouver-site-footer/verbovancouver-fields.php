<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_site_footer',
    'title'    => 'Pie del sitio',
    'fields'   => array(
      array( 'key' => 'field_vv_footer_heading', 'label' => 'Título de cierre', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Te esperamos el sábado' ),
      array( 'key' => 'field_vv_footer_text', 'label' => 'Texto de cierre', 'name' => 'text', 'type' => 'text', 'default_value' => 'Llega como estás. Hay un lugar para ti y para tu familia.' ),
      array( 'key' => 'field_vv_footer_tagline', 'label' => 'Descripción breve', 'name' => 'tagline', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Iglesia cristiana en español en Burnaby. Una familia donde la luz de Cristo habita.' ),
      array(
        'key'          => 'field_vv_footer_links',
        'label'        => 'Enlaces "Explora"',
        'name'         => 'links',
        'type'         => 'repeater',
        'layout'       => 'table',
        'max'          => 8,
        'button_label' => 'Añadir enlace',
        'instructions' => 'Si lo dejas vacío se usan los enlaces por defecto.',
        'sub_fields'   => array(
          array( 'key' => 'field_vv_footer_link', 'label' => 'Enlace', 'name' => 'link', 'type' => 'link', 'return_format' => 'array' ),
        ),
      ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/site-footer' ) ) ),
    'active'   => true,
  ) );

endif;
