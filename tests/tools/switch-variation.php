<?php
// 測試用：模擬在網站編輯器選配色並儲存（寫入 user Global Styles）。
// 用法：wp --user=admin eval-file tests/tools/switch-variation.php <slug|reset>
// 必須帶 --user，否則文章不會掛上佈景分類，每次都新建一篇。
$slug = $args[0];
$id = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();
$data = array( 'version' => 3, 'isGlobalStylesUserThemeJSON' => true );
if ( 'reset' !== $slug ) {
	$v = json_decode( file_get_contents( get_stylesheet_directory() . "/styles/$slug.json" ), true );
	$data['settings'] = $v['settings'];
	$data['title'] = $v['title'];
}
wp_update_post( array( 'ID' => $id, 'post_content' => wp_json_encode( $data ) ) );
WP_Theme_JSON_Resolver::clean_cached_data();
echo "$slug -> post $id\n";
