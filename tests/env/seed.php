<?php
/**
 * 填入測試內容（全部為示範文字，無真實資料）。重複執行不會重複建立。
 * 用法：php tests/env/seed.php .wp-test/site
 */
$site = $argv[1];
$_SERVER['HTTP_HOST'] = 'localhost:8890';
require $site . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

function futheme_seed_post( $type, $title, $content, $extra = array() ) {
	$found = get_page_by_path( sanitize_title( $extra['post_name'] ?? $title ), OBJECT, $type );
	if ( $found ) {
		return $found->ID;
	}
	return wp_insert_post( array_merge( array(
		'post_type'    => $type,
		'post_title'   => $title,
		'post_content' => $content,
		'post_status'  => 'publish',
	), $extra ) );
}

// 測試用橫幅圖：以 GD 產生的漸層，不使用外部圖片。
function futheme_seed_image( $name, $w, $h, $light ) {
	$existing = get_posts( array( 'post_type' => 'attachment', 'name' => $name, 'numberposts' => 1 ) );
	if ( $existing ) {
		return $existing[0]->ID;
	}
	$img = imagecreatetruecolor( $w, $h );
	for ( $x = 0; $x < $w; $x++ ) {
		$v = $light ? 220 + (int) ( 35 * $x / $w ) : 40 + (int) ( 120 * $x / $w );
		$c = imagecolorallocate( $img, $v, $light ? 255 : $v + 20, $light ? 255 : $v + 40 );
		imageline( $img, $x, 0, $x, $h, $c );
	}
	$upload = wp_upload_dir();
	$file   = $upload['path'] . "/$name.jpg";
	imagejpeg( $img, $file, 85 );
	$id = wp_insert_attachment( array( 'post_title' => $name, 'post_name' => $name, 'post_mime_type' => 'image/jpeg', 'post_status' => 'inherit' ), $file );
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
	return $id;
}

$white = futheme_seed_image( 'fu-test-white-banner', 1600, 600, true );
$dark  = futheme_seed_image( 'fu-test-dark-banner', 1600, 600, false );

$cat = wp_create_category( '活動消息' );
$p   = '<!-- wp:paragraph --><p>這是測試用的段落文字，用來檢查行高、字級與<a href="#">內文連結</a>的顯示。</p><!-- /wp:paragraph -->';
for ( $i = 1; $i <= 5; $i++ ) {
	$id = futheme_seed_post( 'post', "測試文章 $i", $p . $p, array( 'post_category' => array( $cat ), 'post_date' => "2026-11-0$i 10:00:00", 'post_name' => "test-post-$i" ) );
	if ( 1 === $i ) {
		set_post_thumbnail( $id, $white ); // 最壞情況：白色底圖。
	}
	if ( 2 === $i ) {
		set_post_thumbnail( $id, $dark );
	}
}
$form = '<!-- wp:html --><form class="fu-test-form" onsubmit="return false"><p><label for="fu-name">姓名</label><br><input id="fu-name" type="text" value=""></p>'
	. '<p><label for="fu-email">Email（錯誤狀態示範）</label><br><input id="fu-email" type="email" aria-invalid="true" aria-describedby="fu-email-err" value="abc"><br><span id="fu-email-err" class="fu-field-error">✕ Email 格式不正確</span></p>'
	. '<p><label for="fu-off">停用欄位</label><br><input id="fu-off" type="text" disabled value="不可編輯"></p>'
	. '<p><button type="submit" class="wp-element-button">送出</button> <button type="button" class="wp-element-button" disabled>停用按鈕</button></p>'
	. '<p class="fu-status-success">✓ 已送出</p><p class="fu-status-warning">! 即將截止</p><p class="fu-status-danger">✕ 送出失敗</p></form><!-- /wp:html -->';
$about   = futheme_seed_post( 'page', '關於我們', $p, array( 'post_name' => 'about' ) );
$contact = futheme_seed_post( 'page', '聯絡我們', $p . $form, array( 'post_name' => 'contact' ) );
$service = futheme_seed_post( 'page', '服務項目', $p, array( 'post_name' => 'services' ) );
set_post_thumbnail( $service, $dark );

if ( ! get_posts( array( 'post_type' => 'wp_navigation', 'numberposts' => 1 ) ) ) {
	$links = '';
	foreach ( array( $about, $service, $contact ) as $pid ) {
		$links .= sprintf( '<!-- wp:navigation-link {"label":"%s","type":"page","id":%d,"url":"%s","kind":"post-type"} /-->', get_the_title( $pid ), $pid, get_permalink( $pid ) );
	}
	wp_insert_post( array( 'post_type' => 'wp_navigation', 'post_title' => '主選單', 'post_content' => $links, 'post_status' => 'publish' ) );
}
echo "seeded\n";
