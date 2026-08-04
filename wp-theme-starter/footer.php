<?php
/**
 * Footer template.
 *
 * @package WP_Theme_Starter
 */
?>
<footer class="site-footer">
	<?php
	$wp_theme_starter_footer_columns = min( 4, max( 1, absint( wp_theme_starter_get_option( 'footer_columns' ) ) ) );
	$wp_theme_starter_has_widgets    = false;

	for ( $i = 1; $i <= $wp_theme_starter_footer_columns; $i++ ) {
		if ( is_active_sidebar( 'footer-' . $i ) ) {
			$wp_theme_starter_has_widgets = true;
			break;
		}
	}
	?>

	<?php if ( $wp_theme_starter_has_widgets ) : ?>
		<div class="site-footer__widgets site-footer__widgets--<?php echo esc_attr( $wp_theme_starter_footer_columns ); ?>">
			<?php
			for ( $i = 1; $i <= $wp_theme_starter_footer_columns; $i++ ) {
				if ( is_active_sidebar( 'footer-' . $i ) ) {
					dynamic_sidebar( 'footer-' . $i );
				}
			}
			?>
		</div>
	<?php endif; ?>

	<div class="site-footer__bar">
		<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer menu', 'wp-theme-starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_id'        => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>

		<p class="site-info">
			<?php
			$wp_theme_starter_footer_text = wp_theme_starter_get_option( 'footer_text' );

			if ( $wp_theme_starter_footer_text ) {
				echo esc_html( $wp_theme_starter_footer_text );
			} else {
				printf(
					'&copy; %1$s <a href="%2$s">%3$s</a>',
					esc_html( gmdate( 'Y' ) ),
					esc_url( home_url( '/' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
			}
			?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
