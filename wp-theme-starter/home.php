<?php
/**
 * Blog index template.
 *
 * @package WP_Theme_Starter
 */

get_header();
?>

<main id="primary" class="site-main">
	<header class="page-header">
		<h1 class="page-title"><?php single_post_title(); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="content-list">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			?>
		</div>

		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
