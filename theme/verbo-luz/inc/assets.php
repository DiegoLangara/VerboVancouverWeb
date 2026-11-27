<?php
/**
 * Carga de CSS/JS. En desarrollo (VERBO_LUZ_VITE_DEV en wp-config.php) usa el servidor de Vite;
 * en producción lee dist/.vite/manifest.json.
 */
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {
	if ( defined( 'VERBO_LUZ_VITE_DEV' ) && VERBO_LUZ_VITE_DEV ) {
		$origin = 'http://localhost:5173';
		wp_enqueue_script( 'verbo-vite', $origin . '/@vite/client', array(), null, false );
		wp_enqueue_script( 'verbo-luz', $origin . '/src/js/main.js', array(), null, true );
		return;
	}

	$manifest_path = VERBO_LUZ_DIR . '/dist/.vite/manifest.json';
	if ( ! file_exists( $manifest_path ) ) {
		return;
	}
	$manifest = json_decode( file_get_contents( $manifest_path ), true );
	$entry    = $manifest['src/js/main.js'] ?? null;
	if ( ! $entry ) {
		return;
	}
	foreach ( $entry['css'] ?? array() as $i => $css ) {
		wp_enqueue_style( 'verbo-luz-' . $i, VERBO_LUZ_URI . '/dist/' . $css, array(), null );
	}
	wp_enqueue_script( 'verbo-luz', VERBO_LUZ_URI . '/dist/' . $entry['file'], array(), null, true );
} );

// Los módulos ES necesitan type="module".
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	if ( in_array( $handle, array( 'verbo-vite', 'verbo-luz' ), true ) ) {
		$tag = str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}, 10, 2 );
