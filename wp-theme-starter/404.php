<?php
/**
 * 404 template.
 *
 * @package WP_Theme_Starter
 */

get_header();
?>

<main id="primary" class="site-main">
	<section class="error-404 not-found">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Page not found', 'wp-theme-starter' ); ?></h1>
		</header>

		<div class="page-content">
			<p><?php esc_html_e( 'The page you requested could not be found. Try searching for what you need.', 'wp-theme-starter' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</section>
</main>

<?php
get_footer();
