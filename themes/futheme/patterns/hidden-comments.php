<?php
/**
 * Title: Comments
 * Slug: futheme/hidden-comments
 * Inserter: no
 * Description: Comment list and native comment form. No avatars, so no external image requests.
 *
 * @package futheme
 */

?>
<!-- wp:comments {"className":"fu-comments","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}}} -->
<div class="wp-block-comments fu-comments" style="margin-top:var(--wp--preset--spacing--70)"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Comments', 'futheme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:comments-title {"level":3,"fontSize":"medium"} /-->

<!-- wp:comment-template -->
<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|50"},"margin":{"bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|20"},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;margin-bottom:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group"><!-- wp:comment-author-name {"fontSize":"small"} /-->

<!-- wp:comment-date {"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:comment-content /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:comment-reply-link {"fontSize":"small"} /-->

<!-- wp:comment-edit-link {"fontSize":"small"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:comment-template -->

<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"space-between"}} -->
<!-- wp:comments-pagination-previous /-->

<!-- wp:comments-pagination-next /-->
<!-- /wp:comments-pagination -->

<!-- wp:post-comments-form /--></div>
<!-- /wp:comments -->
