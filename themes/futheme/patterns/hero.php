<?php
/**
 * Title: Hero banner
 * Slug: futheme/hero
 * Categories: futheme, banner
 * Description: Full-width banner with site title and tagline. Without an image it shows the primary colour; with an image a dark overlay keeps the text readable.
 * Block Types: core/cover
 *
 * @package futheme
 */

?>
<!-- wp:cover {"dimRatio":70,"overlayColor":"contrast","minHeight":60,"minHeightUnit":"vh","align":"full","className":"fu-banner","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull fu-banner" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:60vh"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:site-title {"isLink":false,"style":{"typography":{"textAlign":"center","fontSize":"clamp(2rem, 5vw, 3.25rem)","lineHeight":"1.2"}},"textColor":"primary-contrast"} /-->

<!-- wp:site-tagline {"style":{"typography":{"textAlign":"center"}},"textColor":"primary-contrast","fontSize":"large"} /-->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--60)"><!-- wp:button {"backgroundColor":"primary-contrast","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-primary-contrast-background-color has-text-color has-background wp-element-button" href="#latest"><?php esc_html_e( 'Read the latest news', 'futheme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
