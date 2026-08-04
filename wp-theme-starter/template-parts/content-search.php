<?php
/**
 * Search content template part.
 *
 * @package WP_Theme_Starter
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--search' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
</article>
