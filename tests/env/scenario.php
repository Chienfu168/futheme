<?php
/**
 * 測試情境切換（只在本機測試站）：
 *   logo-on   上傳白色測試 Logo（GD 產生，200×80 透明 PNG）並設為網站標誌
 *   logo-off  移除網站標誌
 *   custom-primary <hex>  模擬管理員在樣式中手動改主色
 */
$site = $argv[1];
$_SERVER['HTTP_HOST'] = 'localhost:' . ( getenv( 'FUTHEME_PORT' ) ?: '8890' );
require $site . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
wp_set_current_user( 1 );
switch ( $argv[2] ) {
	case 'logo-on':
		$img = imagecreatetruecolor( 200, 80 );
		imagesavealpha( $img, true );
		imagefill( $img, 0, 0, imagecolorallocatealpha( $img, 0, 0, 0, 127 ) );
		$white = imagecolorallocate( $img, 255, 255, 255 );
		imagefilledellipse( $img, 40, 40, 64, 64, $white );
		imagefilledrectangle( $img, 84, 26, 190, 54, $white );
		$file = wp_upload_dir()['path'] . '/fu-test-logo.png';
		imagepng( $img, $file );
		$id = wp_insert_attachment( array( 'post_title' => 'fu-test-logo', 'post_mime_type' => 'image/png', 'post_status' => 'inherit' ), $file );
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
		set_theme_mod( 'custom_logo', $id );
		update_option( 'site_logo', $id );
		echo "logo=$id\n";
		break;
	case 'logo-off':
		remove_theme_mod( 'custom_logo' );
		delete_option( 'site_logo' );
		echo "logo=off\n";
		break;
	case 'custom-primary':
		$id   = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();
		$base = json_decode( file_get_contents( get_theme_file_path( 'styles/urban-blue.json' ) ), true );
		foreach ( $base['settings']['color']['palette'] as &$c ) {
			if ( 'primary' === $c['slug'] ) {
				$c['color'] = $argv[3];
			}
		}
		$data = array( 'version' => 3, 'isGlobalStylesUserThemeJSON' => true, 'settings' => array( 'color' => array( 'palette' => array( 'theme' => $base['settings']['color']['palette'] ) ) ) );
		wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( wp_json_encode( $data ) ) ) );
		echo "primary={$argv[3]}\n";
		break;
}
