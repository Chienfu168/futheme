<?php
/**
 * FuTheme：只做呈現。AI、RSS、會員、排程與商業邏輯放在獨立外掛。
 *
 * @package futheme
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'FUTHEME_VERSION' ) ) {
	define( 'FUTHEME_VERSION', '0.5.1' );
}

/**
 * 翻譯與 theme.json 管不到的佈景支援。
 */
function futheme_setup(): void {
	load_theme_textdomain( 'futheme', get_template_directory() . '/languages' );
	// 文章縮圖：單篇／單頁頁首橫幅會用到。
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'futheme_setup' );

/**
 * 佈景自有 Pattern 分類。
 */
function futheme_register_pattern_category(): void {
	register_block_pattern_category(
		'futheme',
		array( 'label' => __( 'FuTheme', 'futheme' ) )
	);
}
add_action( 'init', 'futheme_register_pattern_category' );

/**
 * 導覽「未指定選單」時的保護。
 *
 * 頁首／頁尾的 Navigation 區塊沒有指定選單時，WordPress 會自動列出「所有頁面」。
 * 頁面一多（例如 30 頁以上），頁首會擠成好幾行、頁尾變成一長串。
 * 只在這種自動備援、且項目超過上限時介入：
 * - 頁首（overlayMenu 不是 never）：改為一律收合成選單按鈕，所有頁面仍可到達。
 * - 頁尾（overlayMenu 為 never）：不顯示自動清單（頁首仍可到達所有頁面）。
 * 站長在網站編輯器指定選單後，這段完全不作用。
 */
function futheme_fallback_nav_is_long(): bool {
	static $is_long = null;
	if ( null !== $is_long ) {
		return $is_long;
	}
	$is_long = false;
	if ( ! function_exists( 'block_core_navigation_get_fallback_blocks' ) ) {
		return $is_long;
	}
	$count = 0;
	foreach ( (array) block_core_navigation_get_fallback_blocks() as $block ) {
		if ( empty( $block['blockName'] ) ) {
			continue;
		}
		if ( 'core/page-list' === $block['blockName'] ) {
			// 頁面清單只有最上層會排在選單列，子頁面收在子選單。
			$parent = (int) ( $block['attrs']['parentPageID'] ?? 0 );
			$count += count(
				get_posts(
					array(
						'post_type'      => 'page',
						'post_status'    => 'publish',
						'post_parent'    => $parent,
						'fields'         => 'ids',
						'posts_per_page' => -1,
						'no_found_rows'  => true,
					)
				)
			);
		} else {
			++$count;
		}
	}
	/**
	 * 自動備援導覽最多幾項仍照常顯示。
	 *
	 * @param int $max 預設 6。
	 */
	$max     = (int) apply_filters( 'futheme_fallback_nav_max_items', 6 );
	$is_long = $count > $max;
	return $is_long;
}

/**
 * 是否為「沒指定選單、會走自動備援」的導覽區塊。
 *
 * @param array $block 解析後的區塊。
 */
function futheme_is_fallback_nav( array $block ): bool {
	return 'core/navigation' === ( $block['blockName'] ?? '' )
		&& empty( $block['attrs']['ref'] )
		&& empty( $block['attrs']['navigationMenuId'] )
		&& empty( $block['innerBlocks'] );
}

/**
 * 頁首：自動備援過長時一律收合。
 *
 * @param array $block 解析後的區塊。
 */
function futheme_collapse_long_fallback_nav( array $block ): array {
	if ( futheme_is_fallback_nav( $block )
		&& 'never' !== ( $block['attrs']['overlayMenu'] ?? 'mobile' )
		&& futheme_fallback_nav_is_long() ) {
		$block['attrs']['overlayMenu'] = 'always';
		// 桌機上只有一個漢堡圖示不易辨認，改顯示文字「選單」。
		$block['attrs']['hasIcon'] = false;
	}
	return $block;
}
add_filter( 'render_block_data', 'futheme_collapse_long_fallback_nav' );

/**
 * 頁尾：自動備援過長時不顯示。
 *
 * @param string $content 區塊輸出。
 * @param array  $block   解析後的區塊。
 */
function futheme_hide_long_fallback_nav( string $content, array $block ): string {
	if ( futheme_is_fallback_nav( $block )
		&& 'never' === ( $block['attrs']['overlayMenu'] ?? 'mobile' )
		&& futheme_fallback_nav_is_long() ) {
		return '';
	}
	return $content;
}
add_filter( 'render_block_core/navigation', 'futheme_hide_long_fallback_nav', 10, 2 );

/**
 * 內建橫幅圖的主題代號（assets/images/banner-<代號>-1920.webp）。
 *
 * @return string[]
 */
function futheme_banner_themes(): array {
	return array( 'city', 'nature', 'business', 'community', 'tech', 'culture' );
}

/**
 * 預設橫幅圖：首頁大橫幅與單篇／單頁頁首橫幅（.fu-banner）沒有設定圖片時使用。
 *
 * 圖片為佈景自帶的原創灰階圖，亮度固定在 50～165，疊 80% 主色遮罩，所以跟著配色變色；
 * 主色遮罩下文字最低對比 4.88:1（十套配色、計算值，classic-gold）。
 * 站長在編輯器設定橫幅圖或文章精選圖片後，以站長的圖為準。
 * 換預設圖：add_filter( 'futheme_default_banner', fn() => 'nature' );（city、nature、business、community、tech、culture）
 * 不要預設圖：add_filter( 'futheme_default_banner', '__return_false' );
 *
 * @param string $content 區塊輸出。
 * @param array  $block   解析後的區塊。
 */
function futheme_default_banner_image( string $content, array $block ): string {
	$class = (string) ( $block['attrs']['className'] ?? '' );
	if ( ! preg_match( '/(^|\s)fu-banner(\s|$)/', $class )
		|| str_contains( $content, 'wp-block-cover__image-background' )
		|| str_contains( $content, 'wp-block-cover__video-background' ) ) {
		return $content;
	}
	$choice = apply_filters( 'futheme_default_banner', 'city' );
	if ( ! $choice ) {
		return $content;
	}
	$choice = in_array( $choice, futheme_banner_themes(), true ) ? $choice : 'city';
	$base   = get_theme_file_uri( 'assets/images/banner-' . $choice . '-' );
	$img    = sprintf(
		'<img class="wp-block-cover__image-background fu-banner__default-image" alt="" src="%1$s" srcset="%1$s 1920w, %2$s 960w" sizes="100vw" width="1920" height="800" decoding="async" fetchpriority="high" />',
		esc_url( $base . '1920.webp' ),
		esc_url( $base . '960.webp' )
	);
	// 放在遮罩 <span> 之前，和 WordPress 自己儲存的 Cover 圖片位置相同。
	$out = preg_replace( '#(<span[^>]*wp-block-cover__background[^>]*></span>)#', $img . '$1', $content, 1, $count );
	if ( 1 !== $count ) {
		return $content;
	}
	$tags = new WP_HTML_Tag_Processor( $out );
	if ( $tags->next_tag() ) {
		$tags->add_class( 'fu-banner--default' );
	}
	return $tags->get_updated_html();
}
add_filter( 'render_block_core/cover', 'futheme_default_banner_image', 10, 2 );

/**
 * 頁尾版權年份：段落加 fu-copyright 類別時，把 © 後的年份換成今年，
 * 站長在編輯器儲存頁尾後也不會停在舊年份。
 *
 * @param string $content 區塊輸出。
 * @param array  $block   解析後的區塊。
 */
function futheme_copyright_year( string $content, array $block ): string {
	if ( ! str_contains( (string) ( $block['attrs']['className'] ?? '' ), 'fu-copyright' ) ) {
		return $content;
	}
	return (string) preg_replace( '/©\s*\d{4}/u', '© ' . wp_date( 'Y' ), $content, 1 );
}
add_filter( 'render_block_core/paragraph', 'futheme_copyright_year', 10, 2 );

/**
 * Contact Form 7 相容：錯誤提示與結果訊息改用配色 Token。
 *
 * 只附加在 CF7 自己的樣式表之後，沒裝 CF7 或該頁沒載入 CF7 樣式時不輸出任何東西。
 * 欄位與送出鈕的外觀不需要額外處理（佈景的原生表單樣式已涵蓋）。
 */
function futheme_cf7_compat(): void {
	if ( ! wp_style_is( 'contact-form-7', 'enqueued' ) ) {
		return;
	}
	$css = '.wpcf7 .wpcf7-not-valid-tip{color:var(--wp--preset--color--danger);font-size:var(--wp--preset--font-size--small)}'
		. '.wpcf7 form .wpcf7-response-output{margin-inline:0;padding:.75rem 1rem;border-width:2px;border-radius:.375rem;border-color:var(--wp--preset--color--border)}'
		. '.wpcf7 form.sent .wpcf7-response-output{border-color:var(--wp--preset--color--success)}'
		. '.wpcf7 form.invalid .wpcf7-response-output,.wpcf7 form.unaccepted .wpcf7-response-output,.wpcf7 form.payment-required .wpcf7-response-output{border-color:var(--wp--preset--color--warning)}'
		. '.wpcf7 form.failed .wpcf7-response-output,.wpcf7 form.aborted .wpcf7-response-output,.wpcf7 form.spam .wpcf7-response-output{border-color:var(--wp--preset--color--danger)}';
	wp_add_inline_style( 'contact-form-7', $css );
}
add_action( 'wp_enqueue_scripts', 'futheme_cf7_compat', 20 );

/**
 * 外觀變更後清除頁面快取。
 *
 * 配色、範本、範本組件存在資料庫；快取外掛（例如 WP Fastest Cache）會繼續送出舊頁面與合併後的舊 CSS，
 * 換了配色訪客卻看到舊顏色（2026-11-20 實測確認）。這裡只呼叫各快取外掛公開的清除介面，
 * 沒裝該外掛時什麼都不做。佈景不自己做快取。
 */
function futheme_purge_page_cache(): void {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	// 呼叫的是快取外掛自己的公開 hook，名稱不能加佈景前綴。
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WP Fastest Cache 公開介面；true：連合併的 CSS／JS 一起清。
	do_action( 'wpfc_clear_all_cache', true );
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- LiteSpeed Cache 公開介面。
	do_action( 'litespeed_purge_all' );
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();                 // WP Rocket。
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();                      // W3 Total Cache。
	}
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();                // WP Super Cache。
	}
	/**
	 * 外觀變更、頁面快取已要求清除之後。其他快取外掛可掛這裡。
	 */
	do_action( 'futheme_page_cache_purged' );
}
add_action( 'save_post_wp_global_styles', 'futheme_purge_page_cache' );
add_action( 'save_post_wp_template', 'futheme_purge_page_cache' );
add_action( 'save_post_wp_template_part', 'futheme_purge_page_cache' );
add_action( 'after_switch_theme', 'futheme_purge_page_cache' );

/**
 * FuTheme 本身更新後也清快取（新版 CSS 與 Pattern 才會出現）。
 *
 * @param WP_Upgrader $upgrader 更新器。
 * @param array       $options  更新資訊。
 */
function futheme_purge_after_update( $upgrader, array $options ): void {
	if ( 'theme' === ( $options['type'] ?? '' )
		&& in_array( get_template(), (array) ( $options['themes'] ?? array() ), true ) ) {
		futheme_purge_page_cache();
	}
}
add_action( 'upgrader_process_complete', 'futheme_purge_after_update', 10, 2 );
