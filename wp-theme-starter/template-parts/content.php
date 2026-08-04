<?php
/**
 * Default content template part.
 *
 * @package WP_Theme_Starter
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="entry-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'wp-theme-starter-card',
				array(
					'sizes' => '(max-width: 720px) 100vw, (max-width: 1120px) 50vw, 33vw',
				)
			);
			?>
		</a>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
</article>
