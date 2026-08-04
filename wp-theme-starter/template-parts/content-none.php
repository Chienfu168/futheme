<?php
/**
 * Empty content template part.
 *
 * @package WP_Theme_Starter
 */
?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing found', 'wp-theme-starter' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'No results matched your search. Try another keyword.', 'wp-theme-starter' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No content is available yet.', 'wp-theme-starter' ); ?></p>
		<?php endif; ?>
	</div>
</section>
