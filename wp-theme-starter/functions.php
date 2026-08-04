<?php
/**
 * Theme functions.
 *
 * @package WP_Theme_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_THEME_STARTER_VERSION', '0.1.0' );

require get_template_directory() . '/inc/customizer.php';

/**
 * Return a cache-busting asset version when files are available locally.
 *
 * @param string $path Absolute asset path.
 * @return string
 */
function wp_theme_starter_asset_version( $path ) {
	return file_exists( $path ) ? (string) filemtime( $path ) : WP_THEME_STARTER_VERSION;
}

if ( ! function_exists( 'wp_theme_starter_setup' ) ) {
	/**
	 * Register theme supports and menus.
	 */
	function wp_theme_starter_setup() {
		load_theme_textdomain( 'wp-theme-starter', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'wp-theme-starter-card', 640, 420, true );
		add_image_size( 'wp-theme-starter-hero', 1600, 900, true );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/main.css' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'wp-theme-starter' ),
				'footer'  => esc_html__( 'Footer Menu', 'wp-theme-starter' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'wp_theme_starter_setup' );

/**
 * Set the content width.
 */
function wp_theme_starter_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wp_theme_starter_content_width', 1200 );
}
add_action( 'after_setup_theme', 'wp_theme_starter_content_width', 0 );

/**
 * Enqueue theme assets.
 */
function wp_theme_starter_enqueue_assets() {
	$parent_css_path = get_template_directory() . '/assets/css/main.css';
	$parent_js_path  = get_template_directory() . '/assets/js/main.js';
	$style_handle    = 'wp-theme-starter-style';

	wp_enqueue_style(
		'wp-theme-starter-style',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		wp_theme_starter_asset_version( $parent_css_path )
	);

	if ( is_child_theme() ) {
		$child_css_path = get_stylesheet_directory() . '/assets/css/main.css';

		if ( file_exists( $child_css_path ) ) {
			wp_enqueue_style(
				'wp-theme-starter-child-style',
				get_stylesheet_directory_uri() . '/assets/css/main.css',
				array( 'wp-theme-starter-style' ),
				wp_theme_starter_asset_version( $child_css_path )
			);

			$style_handle = 'wp-theme-starter-child-style';
		}
	}

	wp_enqueue_script(
		'wp-theme-starter-script',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		wp_theme_starter_asset_version( $parent_js_path ),
		true
	);

	wp_add_inline_style( $style_handle, wp_theme_starter_get_customizer_css() );
}
add_action( 'wp_enqueue_scripts', 'wp_theme_starter_enqueue_assets' );

/**
 * Enqueue editor styles for parent and child themes.
 */
function wp_theme_starter_enqueue_editor_assets() {
	$style_handle = 'wp-theme-starter-editor-style';

	wp_enqueue_style(
		'wp-theme-starter-editor-style',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		wp_theme_starter_asset_version( get_template_directory() . '/assets/css/main.css' )
	);

	if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/assets/css/main.css' ) ) {
		wp_enqueue_style(
			'wp-theme-starter-child-editor-style',
			get_stylesheet_directory_uri() . '/assets/css/main.css',
			array( 'wp-theme-starter-editor-style' ),
			wp_theme_starter_asset_version( get_stylesheet_directory() . '/assets/css/main.css' )
		);

		$style_handle = 'wp-theme-starter-child-editor-style';
	}

	wp_add_inline_style( $style_handle, wp_theme_starter_get_customizer_css() );
}
add_action( 'enqueue_block_editor_assets', 'wp_theme_starter_enqueue_editor_assets' );

/**
 * Register widget areas.
 */
function wp_theme_starter_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'wp-theme-starter' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'wp-theme-starter' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				'name'          => sprintf(
					/* translators: %d: footer widget area number. */
					esc_html__( 'Footer Block %d', 'wp-theme-starter' ),
					$i
				),
				'id'            => 'footer-' . $i,
				'description'   => esc_html__( 'Add footer content here. Empty footer blocks are hidden automatically.', 'wp-theme-starter' ),
				'before_widget' => '<section id="%1$s" class="footer-widget widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="footer-widget__title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'wp_theme_starter_widgets_init' );
