<?php
/**
 * FuTheme Core：只做呈現。AI、RSS、會員、排程與商業邏輯放在獨立外掛。
 *
 * @package futheme-core
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'FUTHEME_CORE_VERSION' ) ) {
	define( 'FUTHEME_CORE_VERSION', '0.1.0' );
}

/**
 * 翻譯與 theme.json 管不到的佈景支援。
 */
function futheme_setup(): void {
	load_theme_textdomain( 'futheme-core', get_template_directory() . '/languages' );
	// 文章縮圖：單篇／單頁頁首橫幅會用到。
	add_theme_support( 'post-thumbnails' );
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
