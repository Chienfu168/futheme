<?php
/**
 * FuTheme Core：只做呈現。AI、RSS、會員、排程與商業邏輯放在獨立外掛。
 *
 * @package futheme-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 載入佈景翻譯。
 */
function futheme_setup(): void {
	load_theme_textdomain( 'futheme-core', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'futheme_setup' );

/**
 * 佈景自有 Pattern 分類。
 */
function futheme_register_pattern_category(): void {
	register_block_pattern_category(
		'futheme',
		array( 'label' => __( 'FuTheme', 'futheme-core' ) )
	);
}
add_action( 'init', 'futheme_register_pattern_category' );
