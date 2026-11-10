<?php
/**
 * Title: 首頁大橫幅
 * Slug: futheme-core/front-hero
 * Categories: futheme, banner
 * Description: 首頁橫幅。圖片由各站在編輯器設定（存在各站媒體庫）；未設定時自動改為主色實心底。
 *
 * @package futheme-core
 */

?>
<!-- wp:cover {"dimRatio":70,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":560,"contentPosition":"bottom left","align":"full","className":"fu-hero","style":{"spacing":{"padding":{"top":"8rem","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained","contentSize":"75rem"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-left fu-hero" style="padding-top:8rem;padding-bottom:var(--wp--preset--spacing--80);min-height:560px"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( '用一句話說明這個網站在做什麼', 'futheme-core' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php esc_html_e( '在編輯器中替換這段文字與橫幅圖片。圖片存在本站媒體庫，不會影響其他網站。', 'futheme-core' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"base","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-base-background-color has-text-color has-background wp-element-button"><?php esc_html_e( '了解更多', 'futheme-core' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
