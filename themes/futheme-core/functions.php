<?php
/**
 * FuTheme Core：只負責呈現。不新增設定頁、資料表、REST API 或前台 JS。
 *
 * @package futheme-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 佈景支援與編輯器樣式。Block Theme 其他支援由 WordPress 依 theme.json 自動啟用。
 */
function futheme_setup() {
	load_theme_textdomain( 'futheme-core', get_template_directory() . '/languages' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/futheme.css' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 96,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
}
add_action( 'after_setup_theme', 'futheme_setup' );

/**
 * 前台只載入一份佈景自有 CSS（配色由 WordPress Global Styles 輸出，不載入十份 CSS）。
 */
function futheme_enqueue_styles() {
	$theme = wp_get_theme();
	wp_enqueue_style(
		'futheme-core',
		get_theme_file_uri( 'assets/css/futheme.css' ),
		array(),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'futheme_enqueue_styles' );

/**
 * Pattern 分類。Pattern 檔案放在 patterns/，由 WordPress 自動註冊。
 */
function futheme_register_pattern_category() {
	register_block_pattern_category(
		'futheme',
		array( 'label' => __( 'FuTheme', 'futheme-core' ) )
	);
}
add_action( 'init', 'futheme_register_pattern_category' );
