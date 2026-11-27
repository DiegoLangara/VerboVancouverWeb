<?php defined( 'ABSPATH' ) || exit; ?>
</main>
<footer class="pie">
	<p><?php esc_html_e( 'Sábados 6:00 pm · 7200 Cariboo Road, Burnaby BC', 'verbo-luz' ); ?></p>
	<?php wp_nav_menu( array( 'theme_location' => 'pie', 'container' => false, 'fallback_cb' => false ) ); ?>
	<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
