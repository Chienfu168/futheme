<?php
/**
 * 用 WordPress 正式的上傳安裝流程（Theme_Upgrader）安裝 ZIP，等同後台「上傳佈景」。
 * 用法：php tests/env/zip-install.php <site> <zip> [--overwrite]
 * --overwrite：以 ZIP 取代已安裝版本（更新或退回），等同後台上傳時選「以上傳版本取代」。
 */
$site = $argv[1];
$zip  = $argv[2];
$_SERVER['HTTP_HOST'] = 'localhost:' . ( getenv( 'FUTHEME_PORT' ) ?: '8891' );
require $site . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/misc.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
wp_set_current_user( 1 );
$before = wp_get_theme( 'futheme-core' );
$skin   = new Automatic_Upgrader_Skin();
$up     = new Theme_Upgrader( $skin );
$result = $up->install( $zip, array( 'overwrite_package' => in_array( '--overwrite', $argv, true ) ) );
wp_clean_themes_cache();
$after = wp_get_theme( 'futheme-core' );
if ( true !== $result ) {
	echo 'INSTALL FAILED: ' . ( is_wp_error( $result ) ? $result->get_error_message() : var_export( $result, true ) ) . "\n" . implode( "\n", $skin->get_upgrade_messages() ) . "\n";
	exit( 1 );
}
if ( $after->errors() ) {
	echo 'THEME ERRORS: ' . $after->errors()->get_error_message() . "\n";
	exit( 1 );
}
switch_theme( 'futheme-core' );
printf( "installed %s -> %s ; active=%s ; block_theme=%s ; variations=%d\n", $before->exists() ? $before->get( 'Version' ) : '(none)', $after->get( 'Version' ), get_stylesheet(), $after->is_block_theme() ? 'yes' : 'no', count( WP_Theme_JSON_Resolver::get_style_variations() ) );
