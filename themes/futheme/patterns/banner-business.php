<?php
/**
 * Title: Banner: business
 * Slug: futheme/banner-business
 * Categories: futheme, banner
 * Keywords: banner, hero, cover, business
 * Block Types: core/cover
 * Description: Geometric panels and a rising chart. Suits companies, consulting and finance. Built-in greyscale image tinted by the current palette's primary colour; replace it with your own image any time.
 *
 * @package futheme
 */

?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-business-1920.webp' ) ); ?>","dimRatio":80,"overlayColor":"primary","isUserOverlayColor":true,"minHeight":60,"minHeightUnit":"vh","align":"full","className":"fu-banner","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull fu-banner" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);min-height:60vh"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-business-1920.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-80 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:site-title {"isLink":false,"style":{"typography":{"textAlign":"center","fontSize":"clamp(2rem, 5vw, 3.25rem)","lineHeight":"1.2"}},"textColor":"primary-contrast"} /-->

<!-- wp:site-tagline {"style":{"typography":{"textAlign":"center"}},"textColor":"primary-contrast","fontSize":"large"} /-->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--60)"><!-- wp:button {"backgroundColor":"primary-contrast","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-primary-contrast-background-color has-text-color has-background wp-element-button" href="#latest"><?php esc_html_e( 'Read the latest news', 'futheme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
