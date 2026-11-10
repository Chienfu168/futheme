<?php
/**
 * 安裝測試站並啟用 futheme-core。只在本機測試環境執行。
 * 管理員密碼每次隨機產生，只寫入 .wp-test/admin-password（已列入 .gitignore）。
 */
$site = $argv[1];
$_SERVER['HTTP_HOST'] = 'localhost:8890';
$_SERVER['REQUEST_URI'] = '/';
define( 'WP_INSTALLING', true );
require $site . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

if ( ! is_blog_installed() ) {
	$password = bin2hex( random_bytes( 12 ) );
	wp_install( 'FuTheme 測試站', 'admin', 'admin@example.test', false, '', $password );
	file_put_contents( dirname( $site ) . '/admin-password', $password . "\n" );
	update_option( 'siteurl', 'http://localhost:8890' );
	update_option( 'home', 'http://localhost:8890' );
	update_option( 'permalink_structure', '/%postname%/' );
	update_option( 'WPLANG', '' );
}
require_once ABSPATH . 'wp-admin/includes/plugin.php';
activate_plugin( 'sqlite-database-integration/load.php' );
$theme = wp_get_theme( 'futheme-core' );
if ( $theme->exists() && ! $theme->errors() ) {
	switch_theme( 'futheme-core' );
}
echo 'theme=' . get_stylesheet() . ' wp=' . get_bloginfo( 'version' ) . ' php=' . PHP_VERSION . "\n";
