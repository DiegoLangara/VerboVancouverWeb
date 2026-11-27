<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  $text = function ( $key, $label, $name, $default = '', $width = '' ) {
    return array(
      'key'           => $key,
      'label'         => $label,
      'name'          => $name,
      'type'          => 'text',
      'default_value' => $default,
      'wrapper'       => array( 'width' => $width, 'class' => '', 'id' => '' ),
    );
  };
  $link = function ( $key, $label, $name, $width = '' ) {
    return array(
      'key'           => $key,
      'label'         => $label,
      'name'          => $name,
      'type'          => 'link',
      'return_format' => 'array',
      'wrapper'       => array( 'width' => $width, 'class' => '', 'id' => '' ),
    );
  };

  acf_add_local_field_group( array(
    'key'      => 'group_vv_hero_luz',
    'title'    => 'Hero Luz que habita',
    'fields'   => array(
      $text( 'field_vv_hero_luz_eyebrow', 'Antetítulo', 'eyebrow', 'Iglesia Cristiana Verbo Vancouver' ),
      $text( 'field_vv_hero_luz_heading', 'Título', 'heading', 'Bienvenidos a la familia' ),
      $text( 'field_vv_hero_luz_text', 'Bajada', 'text', 'Un hogar espiritual para latinos en Vancouver. Sábados 6:00 pm.' ),
      $link( 'field_vv_hero_luz_cta_primary', 'Botón principal', 'cta_primary', '50' ),
      $link( 'field_vv_hero_luz_cta_secondary', 'Botón secundario', 'cta_secondary', '50' ),
      array(
        'key'           => 'field_vv_hero_luz_bars',
        'label'         => 'Número de barras de luz',
        'name'          => 'bars',
        'type'          => 'number',
        'instructions'  => 'Número impar para que la cruz quede al centro.',
        'default_value' => 13,
        'min'           => 5,
        'max'           => 21,
        'step'          => 2,
      ),
    ),
    'location' => array(
      array(
        array(
          'param'    => 'block',
          'operator' => '==',
          'value'    => 'verbovancouver/hero-luz',
        ),
      ),
    ),
    'active'   => true,
  ) );

endif;
