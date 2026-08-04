<?php
/**
 * Single post content template part.
 *
 * @package WP_Theme_Starter
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail">
			<?php
			the_post_thumbnail(
				'wp-theme-starter-hero',
				array(
					'sizes' => '(max-width: 1120px) 100vw, 1120px',
				)
			);
			?>
		</div>
	<?php endif; ?>

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
