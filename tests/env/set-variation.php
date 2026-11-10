<?php
/**
 * 模擬管理員在「外觀 → 編輯器 → 樣式」選擇配色：把 variation 寫入使用者 Global Styles。
 * 用法：php tests/env/set-variation.php .wp-test/site <slug|reset>
 */
$site = $argv[1];
$slug = $argv[2];
$_SERVER['HTTP_HOST'] = 'localhost:' . ( getenv( 'FUTHEME_PORT' ) ?: '8890' );
require $site . '/wp-load.php';
// 以管理員身分寫入（等同後台操作）；匿名身分會被 kses 過濾掉設定。
wp_set_current_user( 1 );
$id   = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();
$data = array( 'version' => 3, 'isGlobalStylesUserThemeJSON' => true );
if ( 'reset' !== $slug ) {
	$file = get_theme_file_path( "styles/$slug.json" );
	$v    = json_decode( file_get_contents( $file ), true );
	$data['settings'] = $v['settings'];
	$data['title']    = $v['title'];
}
wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( wp_json_encode( $data ) ) ) );
wp_clean_theme_json_cache();
echo "variation=$slug post=$id\n";
