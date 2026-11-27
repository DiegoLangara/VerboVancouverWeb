<?php
/**
 * Tipos de contenido del sitio: Prédicas, Eventos, Ministerios, IGHO, Testimonios y Líderes.
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	$tipos = array(
		'predica'     => array( 'Prédicas', 'Prédica', 'predicas', 'dashicons-microphone' ),
		'evento'      => array( 'Eventos', 'Evento', 'eventos', 'dashicons-calendar-alt' ),
		'ministerio'  => array( 'Ministerios', 'Ministerio', 'ministerios', 'dashicons-groups' ),
		'igho'        => array( 'IGHO', 'IGHO', 'igho', 'dashicons-admin-home' ),
		'testimonio'  => array( 'Testimonios', 'Testimonio', 'testimonios', 'dashicons-format-quote' ),
		'lider'       => array( 'Líderes', 'Líder', 'lideres', 'dashicons-businessperson' ),
	);

	foreach ( $tipos as $tipo => list( $plural, $singular, $slug, $icono ) ) {
		register_post_type( $tipo, array(
			'labels'       => array(
				'name'          => $plural,
				'singular_name' => $singular,
				'add_new_item'  => 'Añadir ' . strtolower( $singular ),
				'edit_item'     => 'Editar ' . strtolower( $singular ),
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => $slug ),
			'menu_icon'    => $icono,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		) );
	}

	register_taxonomy( 'serie', 'predica', array(
		'labels'       => array( 'name' => 'Series', 'singular_name' => 'Serie' ),
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'series' ),
	) );
} );
