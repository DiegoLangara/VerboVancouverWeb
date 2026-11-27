<?php
/**
 * Inicio: primer bloque del recorrido "de la calle a la familia".
 * Las barras de luz se generan aquí y las anima src/js/hero.js.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="hero" data-hero>
	<div class="hero__luces" aria-hidden="true">
		<?php for ( $i = 0; $i < 13; $i++ ) : ?>
			<span class="hero__barra<?php echo 6 === $i ? ' hero__barra--cruz' : ''; ?>"></span>
		<?php endfor; ?>
		<span class="hero__travesano"></span>
	</div>
	<div class="hero__texto">
		<p class="hero__antetitulo"><?php esc_html_e( 'Iglesia Cristiana Verbo Vancouver', 'verbo-luz' ); ?></p>
		<h1 class="hero__titulo" data-revelar><?php esc_html_e( 'Bienvenidos a la familia', 'verbo-luz' ); ?></h1>
		<p class="hero__bajada"><?php esc_html_e( 'Un hogar espiritual para latinos en Vancouver. Sábados 6:00 pm.', 'verbo-luz' ); ?></p>
		<div class="hero__acciones">
			<a class="boton boton--luz" href="<?php echo esc_url( home_url( '/visitanos/' ) ); ?>"><?php esc_html_e( 'Planea tu visita', 'verbo-luz' ); ?></a>
			<a class="boton boton--linea" href="<?php echo esc_url( home_url( '/predicas/' ) ); ?>"><?php esc_html_e( 'Ver la última prédica', 'verbo-luz' ); ?></a>
		</div>
	</div>
</section>

<section class="pilares seccion">
	<?php
	$pilares = array(
		array( 'Adoración', 'Cultos vibrantes en español.' ),
		array( 'Comunidad', 'Grupos pequeños, familias y jóvenes.' ),
		array( 'Crecimiento', 'Predicaciones y estudios bíblicos.' ),
	);
	foreach ( $pilares as list( $titulo, $texto ) ) :
		?>
		<article class="pilar" data-revelar>
			<h2 class="pilar__titulo"><?php echo esc_html( $titulo ); ?></h2>
			<p><?php echo esc_html( $texto ); ?></p>
		</article>
	<?php endforeach; ?>
</section>
<?php
get_footer();
