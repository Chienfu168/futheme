<?php
// PHP 內建伺服器的路由：實體檔案直接回傳，目錄找 index.php，其餘交給 WordPress（支援固定網址）。
$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$file = getcwd() . $path;
if ( is_dir( $file ) && is_file( rtrim( $file, '/' ) . '/index.php' ) ) {
	$path = rtrim( $path, '/' ) . '/index.php';
	$file = getcwd() . $path;
}
if ( is_file( $file ) && ! str_ends_with( $path, '.php' ) ) {
	return false;
}
if ( is_file( $file ) ) {
	$_SERVER['SCRIPT_NAME'] = $path;
	$_SERVER['PHP_SELF']    = $path;
	chdir( dirname( $file ) );
	require $file;
	return;
}
$_SERVER['SCRIPT_NAME'] = '/index.php';
require getcwd() . '/index.php';
