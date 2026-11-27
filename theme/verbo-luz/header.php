<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="saltar" href="#contenido"><?php esc_html_e( 'Saltar al contenido', 'verbo-luz' ); ?></a>
<header class="cabecera">
	<a class="cabecera__marca" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( has_custom_logo() ) {
			echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'medium', false, array( 'alt' => get_bloginfo( 'name' ) ) );
		} else {
			echo '<span>VERBO</span><small>VANCOUVER</small>';
		}
		?>
	</a>
	<nav class="cabecera__nav" aria-label="<?php esc_attr_e( 'Principal', 'verbo-luz' ); ?>">
		<?php wp_nav_menu( array( 'theme_location' => 'principal', 'container' => false, 'fallback_cb' => false ) ); ?>
	</nav>
	<a class="boton boton--luz" href="<?php echo esc_url( home_url( '/visitanos/' ) ); ?>"><?php esc_html_e( 'Planea tu visita', 'verbo-luz' ); ?></a>
</header>
<main id="contenido">
