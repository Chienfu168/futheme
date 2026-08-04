<?php
/**
 * Template Name: Full Width
 *
 * @package WP_Theme_Starter
 */

get_header();
?>

<main id="primary" class="site-main site-main--wide">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', 'page' );
	endwhile;
	?>
</main>

<?php
get_footer();
