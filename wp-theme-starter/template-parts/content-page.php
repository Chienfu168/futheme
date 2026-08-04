<?php
/**
 * Page content template part.
 *
 * @package WP_Theme_Starter
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wp-theme-starter' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article>
