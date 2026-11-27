<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="seccion seccion--clara">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'entrada' ); ?>>
				<h1 class="titulo" data-revelar><?php the_title(); ?></h1>
				<div class="entrada__contenido"><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No encontramos contenido aquí.', 'verbo-luz' ); ?></p>
	<?php endif; ?>
</section>
<?php
get_footer();
