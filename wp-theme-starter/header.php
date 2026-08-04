<?php
/**
 * Header template.
 *
 * @package WP_Theme_Starter
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wp-theme-starter' ); ?></a>

<header class="site-header <?php echo is_front_page() ? 'site-header--front' : 'site-header--inner'; ?>">
	<div class="site-header__inner">
		<div class="site-branding">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			}

			if ( is_front_page() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>

			<?php
			$wp_theme_starter_description = get_bloginfo( 'description', 'display' );
			if ( $wp_theme_starter_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo esc_html( $wp_theme_starter_description ); ?></p>
			<?php endif; ?>
		</div>

		<nav class="primary-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'wp-theme-starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</header>

<?php if ( ! is_front_page() && ! is_404() ) : ?>
	<div class="page-masthead">
		<div class="page-masthead__inner">
			<?php if ( is_singular() ) : ?>
				<?php $wp_theme_starter_post_type = get_post_type_object( get_post_type() ); ?>
				<?php if ( $wp_theme_starter_post_type ) : ?>
					<p class="page-masthead__label"><?php echo esc_html( $wp_theme_starter_post_type->labels->singular_name ); ?></p>
				<?php endif; ?>
			<?php elseif ( is_archive() ) : ?>
				<p class="page-masthead__label"><?php esc_html_e( 'Archive', 'wp-theme-starter' ); ?></p>
			<?php elseif ( is_search() ) : ?>
				<p class="page-masthead__label"><?php esc_html_e( 'Search', 'wp-theme-starter' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
