<?php
/**
 * Front page template.
 *
 * @package WP_Theme_Starter
 */

get_header();
?>

<main id="primary" class="site-main site-main--front">
	<section class="front-hero">
		<div class="front-hero__content">
			<?php
			$wp_theme_starter_eyebrow = wp_theme_starter_get_option( 'hero_eyebrow' );
			$wp_theme_starter_title   = wp_theme_starter_get_option( 'hero_title' );
			$wp_theme_starter_summary = wp_theme_starter_get_option( 'hero_summary' );
			?>

			<?php if ( $wp_theme_starter_eyebrow || get_bloginfo( 'description' ) ) : ?>
				<p class="front-hero__eyebrow">
					<?php echo esc_html( $wp_theme_starter_eyebrow ? $wp_theme_starter_eyebrow : get_bloginfo( 'description' ) ); ?>
				</p>
			<?php endif; ?>

			<h1 class="front-hero__title">
				<?php echo esc_html( $wp_theme_starter_title ? $wp_theme_starter_title : get_bloginfo( 'name' ) ); ?>
			</h1>

			<?php if ( $wp_theme_starter_summary ) : ?>
				<div class="front-hero__summary">
					<?php echo wp_kses_post( wpautop( $wp_theme_starter_summary ) ); ?>
				</div>
			<?php elseif ( get_option( 'page_on_front' ) ) : ?>
				<div class="front-hero__summary">
					<?php
					while ( have_posts() ) :
						the_post();
						the_excerpt();
					endwhile;
					rewind_posts();
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( have_posts() ) : ?>
		<section class="front-content">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<?php
			endwhile;
			?>
		</section>
	<?php endif; ?>

	<?php if ( wp_theme_starter_get_option( 'show_latest_posts' ) ) : ?>
		<section class="front-latest">
			<header class="section-header">
				<h2><?php echo esc_html( wp_theme_starter_get_option( 'latest_posts_title' ) ); ?></h2>
			</header>

			<div class="content-list content-list--cards">
				<?php
				$wp_theme_starter_latest = new WP_Query(
					array(
						'posts_per_page'      => wp_theme_starter_get_option( 'latest_posts_count' ),
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
					)
				);

				if ( $wp_theme_starter_latest->have_posts() ) :
					while ( $wp_theme_starter_latest->have_posts() ) :
						$wp_theme_starter_latest->the_post();
						get_template_part( 'template-parts/content', get_post_type() );
					endwhile;
					wp_reset_postdata();
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
