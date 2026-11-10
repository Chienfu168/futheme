<?php
/**
 * FuTheme Core
 *
 * 這裡刻意只做 Block Theme 自己不會做的事。
 * 能用 theme.json 做到的一律不寫 PHP——那是 docs/requirements.md 第二節定的。
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
 * 佈景支援。
 *
 * 多數 Block Theme 的支援項目（editor-styles、responsive embeds、
 * align-wide 等）在有 theme.json 時由核心自動開啟，不需要在這裡宣告。
 * 留在這裡的只有兩件 theme.json 管不到的事。
 */
function futheme_core_setup(): void {
	// 翻譯。text domain 與資料夾名稱一致。
	load_theme_textdomain( 'futheme-core', get_template_directory() . '/languages' );

	// 文章縮圖：theme.json 沒有這個設定項。
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'futheme_core_setup' );
