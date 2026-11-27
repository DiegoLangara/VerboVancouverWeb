<?php

if ( function_exists( 'acf_add_local_field_group' ) ) :

  acf_add_local_field_group( array(
    'key'      => 'group_vv_predica',
    'title'    => 'Última prédica',
    'fields'   => array(
      array( 'key' => 'field_vv_predica_eyebrow', 'label' => 'Antetítulo', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Prédica más reciente' ),
      array( 'key' => 'field_vv_predica_heading', 'label' => 'Título de la prédica', 'name' => 'heading', 'type' => 'text' ),
      array( 'key' => 'field_vv_predica_speaker', 'label' => 'Predicador y fecha', 'name' => 'speaker', 'type' => 'text' ),
      array( 'key' => 'field_vv_predica_video', 'label' => 'Video de YouTube (URL)', 'name' => 'video', 'type' => 'url', 'instructions' => 'Pega el enlace del video o de la transmisión.' ),
      array( 'key' => 'field_vv_predica_image', 'label' => 'Imagen de fondo', 'name' => 'image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'instructions' => 'Imagen que se ve detrás del botón de reproducir. Si la dejas vacía, se usa la miniatura del video de YouTube.' ),
      array( 'key' => 'field_vv_predica_channel', 'label' => 'Canal de YouTube (URL)', 'name' => 'channel', 'type' => 'url', 'default_value' => 'https://www.youtube.com/channel/UC_vqNqKT2nOjLyubHzqe7Kg' ),
    ),
    'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'verbovancouver/predica' ) ) ),
    'active'   => true,
  ) );

endif;
