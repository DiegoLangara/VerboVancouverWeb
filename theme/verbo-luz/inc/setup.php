<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'verbo-luz', VERBO_LUZ_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'principal' => __( 'Menú principal', 'verbo-luz' ),
		'pie'       => __( 'Menú del pie', 'verbo-luz' ),
	) );
} );
