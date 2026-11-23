<?php
/**
 * FuTheme 線上更新：讀 GitHub 上 main 分支的 update.json，在後台顯示「有可用更新」。
 *
 * 運作方式：
 * - style.css 的 `Update URI: https://github.com/Chienfu168/futheme` 讓 WordPress 改問
 *   `update_themes_github.com` 這個 hook，而不是 wordpress.org（不會被同名佈景誤更新）。
 * - 版本檔只放在 main 分支：工作分支的修改不會推給網站，合併進 main 才算發布。
 * - 下載後先比對 SHA-256，不符就不安裝。
 * - 只用 WordPress 內建的 HTTP 與更新機制；不需要 token，不送出任何網站資料。
 *
 * @package futheme
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 版本檔網址。測試站可用濾鏡 `futheme_update_manifest_url` 指到本機檔案。
 */
function futheme_update_manifest_url(): string {
	return (string) apply_filters(
		'futheme_update_manifest_url',
		'https://raw.githubusercontent.com/Chienfu168/futheme/main/update.json'
	);
}

/**
 * 下載網址只接受 https；本機測試站（WP_ENVIRONMENT_TYPE 為 local）另外允許 http://localhost。
 *
 * @param string $url 下載網址。
 */
function futheme_update_url_allowed( string $url ): bool {
	return str_starts_with( $url, 'https://' )
		|| ( 'local' === wp_get_environment_type() && 1 === preg_match( '#^http://localhost(:\d+)?/#', $url ) );
}

/**
 * 讀取並驗證版本檔；成功快取 12 小時，失敗快取 1 小時（避免每次進後台都去連線）。
 *
 * @return array|null 版本資訊；取不到或格式不對時為 null。
 */
function futheme_update_manifest(): ?array {
	$cached = get_site_transient( 'futheme_update_manifest' );
	if ( is_array( $cached ) ) {
		return $cached['ok'] ? $cached['data'] : null;
	}
	$data     = null;
	$response = wp_remote_get(
		futheme_update_manifest_url(),
		array(
			'timeout'    => 5,
			'user-agent' => 'FuTheme-Updater',
		)
	);
	if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
		$json = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( is_array( $json )
			&& isset( $json['version'], $json['package'], $json['sha256'] )
			&& preg_match( '/^\d+\.\d+\.\d+$/', (string) $json['version'] )
			&& preg_match( '/^[0-9a-f]{64}$/', (string) $json['sha256'] )
			&& futheme_update_url_allowed( (string) $json['package'] ) ) {
			$data = $json;
		}
	}
	set_site_transient(
		'futheme_update_manifest',
		array(
			'ok'   => null !== $data,
			'data' => $data,
		),
		null !== $data ? 12 * HOUR_IN_SECONDS : HOUR_IN_SECONDS
	);
	return $data;
}

/**
 * 回報更新資訊給 WordPress（版本比較由 WordPress 自己做）。
 *
 * @param array|false $update           其他程式給的更新資訊，預設 false。
 * @param array       $theme_data       佈景標頭。
 * @param string      $theme_stylesheet 佈景目錄名稱。
 * @return array|false
 */
function futheme_update_check( $update, array $theme_data, string $theme_stylesheet ) {
	// 其他佈景也可能用 github.com 當 Update URI，只處理本佈景。
	if ( 'futheme' !== $theme_stylesheet || ! empty( $update ) ) {
		return $update;
	}
	$manifest = futheme_update_manifest();
	if ( null === $manifest ) {
		return $update;
	}
	return array(
		'theme'        => 'futheme',
		'version'      => (string) $manifest['version'],
		'url'          => (string) ( $manifest['url'] ?? 'https://github.com/Chienfu168/futheme' ),
		'package'      => (string) $manifest['package'],
		'tested'       => (string) ( $manifest['tested'] ?? '' ),
		'requires'     => (string) ( $manifest['requires'] ?? '' ),
		'requires_php' => (string) ( $manifest['requires_php'] ?? '' ),
	);
}
add_filter( 'update_themes_github.com', 'futheme_update_check', 10, 3 );

/**
 * 下載本佈景的更新檔時比對 SHA-256，不符就中止，不會安裝。
 *
 * @param bool|string|WP_Error $reply   其他程式的處理結果，預設 false。
 * @param string               $package 下載網址。
 * @return bool|string|WP_Error 下載好的暫存檔路徑，或錯誤。
 */
function futheme_verify_package( $reply, $package ) {
	if ( false !== $reply ) {
		return $reply;
	}
	$manifest = futheme_update_manifest();
	if ( null === $manifest || (string) $package !== (string) $manifest['package'] ) {
		return $reply;
	}
	$file = download_url( (string) $package, 60 );
	if ( is_wp_error( $file ) ) {
		return $file;
	}
	if ( ! hash_equals( (string) $manifest['sha256'], (string) hash_file( 'sha256', $file ) ) ) {
		wp_delete_file( $file );
		return new WP_Error( 'futheme_checksum', __( 'The FuTheme update file did not match its checksum and was not installed.', 'futheme' ) );
	}
	return $file;
}
add_filter( 'upgrader_pre_download', 'futheme_verify_package', 10, 2 );

/**
 * 佈景更新完成或手動按「檢查更新」時，丟掉快取的版本檔，下一次重新讀取。
 */
function futheme_forget_manifest(): void {
	delete_site_transient( 'futheme_update_manifest' );
}
add_action( 'upgrader_process_complete', 'futheme_forget_manifest' );
add_action( 'load-update-core.php', 'futheme_forget_manifest' );
