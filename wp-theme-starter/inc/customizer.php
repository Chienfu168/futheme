<?php
/**
 * Customizer settings.
 *
 * @package WP_Theme_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return default Customizer values.
 *
 * @return array
 */
function wp_theme_starter_get_customizer_defaults() {
	return array(
		'primary_color'         => '#176b5c',
		'accent_color'          => '#b45309',
		'text_color'            => '#1f2933',
		'muted_color'           => '#687076',
		'background_color'      => '#ffffff',
		'surface_color'         => '#f7f7f5',
		'border_color'          => '#d8ddd8',
		'hero_background_color' => '#f7f7f5',
		'hero_text_color'       => '#1f2933',
		'content_width'         => 1120,
		'wide_width'            => 1280,
		'border_radius'         => 8,
		'section_spacing'       => 64,
		'card_image_ratio'      => '16 / 10',
		'hero_image_ratio'      => '16 / 9',
		'hero_eyebrow'          => '',
		'hero_title'            => '',
		'hero_summary'          => '',
		'show_latest_posts'     => true,
		'latest_posts_title'    => __( 'Latest Posts', 'wp-theme-starter' ),
		'latest_posts_count'    => 3,
		'footer_columns'        => 3,
		'footer_text'           => '',
	);
}

/**
 * Get a theme mod with a default value.
 *
 * @param string $key Setting key.
 * @return mixed
 */
function wp_theme_starter_get_option( $key ) {
	$defaults = wp_theme_starter_get_customizer_defaults();

	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Sanitize checkbox values.
 *
 * @param mixed $checked Checkbox value.
 * @return bool
 */
function wp_theme_starter_sanitize_checkbox( $checked ) {
	return (bool) $checked;
}

/**
 * Sanitize integer values.
 *
 * @param mixed $value Number value.
 * @return int
 */
function wp_theme_starter_sanitize_integer( $value ) {
	return absint( $value );
}

/**
 * Sanitize latest posts count.
 *
 * @param mixed $value Number value.
 * @return int
 */
function wp_theme_starter_sanitize_posts_count( $value ) {
	$value = absint( $value );

	if ( $value < 1 ) {
		return 1;
	}

	if ( $value > 12 ) {
		return 12;
	}

	return $value;
}

/**
 * Sanitize footer column count.
 *
 * @param mixed $value Number value.
 * @return int
 */
function wp_theme_starter_sanitize_footer_columns( $value ) {
	$value = absint( $value );

	if ( $value < 1 ) {
		return 1;
	}

	if ( $value > 4 ) {
		return 4;
	}

	return $value;
}

/**
 * Sanitize aspect ratio choices.
 *
 * @param string $value Ratio value.
 * @return string
 */
function wp_theme_starter_sanitize_aspect_ratio( $value ) {
	$allowed = array( '16 / 9', '16 / 10', '4 / 3', '3 / 2', '1 / 1' );

	return in_array( $value, $allowed, true ) ? $value : '16 / 9';
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function wp_theme_starter_customize_register( $wp_customize ) {
	$defaults = wp_theme_starter_get_customizer_defaults();

	$wp_customize->add_panel(
		'wp_theme_starter_design',
		array(
			'title'       => __( 'Theme Design', 'wp-theme-starter' ),
			'description' => __( 'Adjust brand, layout, and homepage display settings without editing theme files.', 'wp-theme-starter' ),
			'priority'    => 30,
		)
	);

	$wp_customize->add_section(
		'wp_theme_starter_colors',
		array(
			'title' => __( 'Brand Colors', 'wp-theme-starter' ),
			'panel' => 'wp_theme_starter_design',
		)
	);

	$color_settings = array(
		'primary_color'         => __( 'Primary Color', 'wp-theme-starter' ),
		'accent_color'          => __( 'Accent Color', 'wp-theme-starter' ),
		'text_color'            => __( 'Text Color', 'wp-theme-starter' ),
		'muted_color'           => __( 'Muted Text Color', 'wp-theme-starter' ),
		'background_color'      => __( 'Page Background', 'wp-theme-starter' ),
		'surface_color'         => __( 'Surface Color', 'wp-theme-starter' ),
		'border_color'          => __( 'Border Color', 'wp-theme-starter' ),
		'hero_background_color' => __( 'Hero Background', 'wp-theme-starter' ),
		'hero_text_color'       => __( 'Hero Text Color', 'wp-theme-starter' ),
	);

	foreach ( $color_settings as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $defaults[ $setting_id ],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => $label,
					'section' => 'wp_theme_starter_colors',
				)
			)
		);
	}

	$wp_customize->add_section(
		'wp_theme_starter_layout',
		array(
			'title' => __( 'Layout', 'wp-theme-starter' ),
			'panel' => 'wp_theme_starter_design',
		)
	);

	$number_settings = array(
		'content_width'   => array( __( 'Content Width', 'wp-theme-starter' ), 720, 1440, 10 ),
		'wide_width'      => array( __( 'Wide Content Width', 'wp-theme-starter' ), 960, 1680, 10 ),
		'border_radius'   => array( __( 'Border Radius', 'wp-theme-starter' ), 0, 24, 1 ),
		'section_spacing' => array( __( 'Section Spacing', 'wp-theme-starter' ), 32, 120, 4 ),
	);

	foreach ( $number_settings as $setting_id => $args ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $defaults[ $setting_id ],
				'sanitize_callback' => 'wp_theme_starter_sanitize_integer',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'type'        => 'number',
				'label'       => $args[0],
				'section'     => 'wp_theme_starter_layout',
				'input_attrs' => array(
					'min'  => $args[1],
					'max'  => $args[2],
					'step' => $args[3],
				),
			)
		);
	}

	$ratio_choices = array(
		'16 / 9'  => __( '16:9 Wide', 'wp-theme-starter' ),
		'16 / 10' => __( '16:10 Card', 'wp-theme-starter' ),
		'4 / 3'   => __( '4:3 Standard', 'wp-theme-starter' ),
		'3 / 2'   => __( '3:2 Photo', 'wp-theme-starter' ),
		'1 / 1'   => __( '1:1 Square', 'wp-theme-starter' ),
	);

	$wp_customize->add_setting(
		'card_image_ratio',
		array(
			'default'           => $defaults['card_image_ratio'],
			'sanitize_callback' => 'wp_theme_starter_sanitize_aspect_ratio',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'card_image_ratio',
		array(
			'type'    => 'select',
			'label'   => __( 'Card Image Ratio', 'wp-theme-starter' ),
			'section' => 'wp_theme_starter_layout',
			'choices' => $ratio_choices,
		)
	);

	$wp_customize->add_setting(
		'hero_image_ratio',
		array(
			'default'           => $defaults['hero_image_ratio'],
			'sanitize_callback' => 'wp_theme_starter_sanitize_aspect_ratio',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'hero_image_ratio',
		array(
			'type'    => 'select',
			'label'   => __( 'Featured Image Ratio', 'wp-theme-starter' ),
			'section' => 'wp_theme_starter_layout',
			'choices' => $ratio_choices,
		)
	);

	$wp_customize->add_section(
		'wp_theme_starter_front_page',
		array(
			'title' => __( 'Front Page', 'wp-theme-starter' ),
			'panel' => 'wp_theme_starter_design',
		)
	);

	$text_settings = array(
		'hero_eyebrow'       => __( 'Hero Eyebrow', 'wp-theme-starter' ),
		'hero_title'         => __( 'Hero Title', 'wp-theme-starter' ),
		'latest_posts_title' => __( 'Latest Posts Title', 'wp-theme-starter' ),
	);

	foreach ( $text_settings as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $defaults[ $setting_id ],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'type'    => 'text',
				'label'   => $label,
				'section' => 'wp_theme_starter_front_page',
			)
		);
	}

	$wp_customize->add_setting(
		'hero_summary',
		array(
			'default'           => $defaults['hero_summary'],
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'hero_summary',
		array(
			'type'    => 'textarea',
			'label'   => __( 'Hero Summary', 'wp-theme-starter' ),
			'section' => 'wp_theme_starter_front_page',
		)
	);

	$wp_customize->add_setting(
		'show_latest_posts',
		array(
			'default'           => $defaults['show_latest_posts'],
			'sanitize_callback' => 'wp_theme_starter_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'show_latest_posts',
		array(
			'type'    => 'checkbox',
			'label'   => __( 'Show Latest Posts', 'wp-theme-starter' ),
			'section' => 'wp_theme_starter_front_page',
		)
	);

	$wp_customize->add_setting(
		'latest_posts_count',
		array(
			'default'           => $defaults['latest_posts_count'],
			'sanitize_callback' => 'wp_theme_starter_sanitize_posts_count',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'latest_posts_count',
		array(
			'type'        => 'number',
			'label'       => __( 'Latest Posts Count', 'wp-theme-starter' ),
			'section'     => 'wp_theme_starter_front_page',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_section(
		'wp_theme_starter_footer',
		array(
			'title' => __( 'Footer', 'wp-theme-starter' ),
			'panel' => 'wp_theme_starter_design',
		)
	);

	$wp_customize->add_setting(
		'footer_columns',
		array(
			'default'           => $defaults['footer_columns'],
			'sanitize_callback' => 'wp_theme_starter_sanitize_footer_columns',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_columns',
		array(
			'type'        => 'number',
			'label'       => __( 'Footer Block Count', 'wp-theme-starter' ),
			'description' => __( 'Use 1 to 4 blocks. Empty widget areas are hidden.', 'wp-theme-starter' ),
			'section'     => 'wp_theme_starter_footer',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 4,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'footer_text',
		array(
			'default'           => $defaults['footer_text'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_text',
		array(
			'type'    => 'text',
			'label'   => __( 'Fixed Footer Text', 'wp-theme-starter' ),
			'section' => 'wp_theme_starter_footer',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'footer_text',
		array(
			'selector' => '.site-info',
		)
	);
}
add_action( 'customize_register', 'wp_theme_starter_customize_register' );

/**
 * Generate CSS variable overrides from Customizer settings.
 *
 * @return string
 */
function wp_theme_starter_get_customizer_css() {
	$css_vars = array(
		'--color-background'      => wp_theme_starter_get_option( 'background_color' ),
		'--color-surface'         => wp_theme_starter_get_option( 'surface_color' ),
		'--color-text'            => wp_theme_starter_get_option( 'text_color' ),
		'--color-muted'           => wp_theme_starter_get_option( 'muted_color' ),
		'--color-border'          => wp_theme_starter_get_option( 'border_color' ),
		'--color-primary'         => wp_theme_starter_get_option( 'primary_color' ),
		'--color-accent'          => wp_theme_starter_get_option( 'accent_color' ),
		'--color-hero-background' => wp_theme_starter_get_option( 'hero_background_color' ),
		'--color-hero-text'       => wp_theme_starter_get_option( 'hero_text_color' ),
		'--content-width'         => wp_theme_starter_get_option( 'content_width' ) . 'px',
		'--wide-width'            => wp_theme_starter_get_option( 'wide_width' ) . 'px',
		'--radius'                => wp_theme_starter_get_option( 'border_radius' ) . 'px',
		'--space-section'         => wp_theme_starter_get_option( 'section_spacing' ) . 'px',
		'--image-card-ratio'      => wp_theme_starter_get_option( 'card_image_ratio' ),
		'--image-hero-ratio'      => wp_theme_starter_get_option( 'hero_image_ratio' ),
	);

	$css = ':root{';

	foreach ( $css_vars as $property => $value ) {
		$css .= esc_html( $property ) . ':' . esc_html( $value ) . ';';
	}

	$css .= '}';

	return $css;
}
