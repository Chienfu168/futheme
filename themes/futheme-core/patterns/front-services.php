<?php
/**
 * Title: 服務卡片（淺底區段）
 * Slug: futheme-core/front-services
 * Categories: futheme, services
 * Description: fu-section-surface 區段，三欄服務卡片。
 *
 * @package futheme-core
 */

?>
<!-- wp:group {"align":"full","className":"is-style-fu-section-surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"75rem"}} -->
<div class="wp-block-group alignfull is-style-fu-section-surface" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( '服務項目', 'futheme-core' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><?php foreach ( array( __( '服務一', 'futheme-core' ), __( '服務二', 'futheme-core' ), __( '服務三', 'futheme-core' ) ) as $futheme_title ) : ?><!-- wp:column {"className":"fu-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"base"} -->
<div class="wp-block-column fu-card has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $futheme_title ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( '用兩三句話說明這項服務，以及誰會需要它。', 'futheme-core' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --><?php endforeach; ?></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
