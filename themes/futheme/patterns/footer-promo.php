<?php
/**
 * Title: Footer: with partner link
 * Slug: futheme/footer-promo
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Simple footer whose credit line also links to the partner site ur-promoter.com.
 *
 * @package futheme
 */

$futheme_credit = sprintf(
	/* translators: 1: theme author name, linked to the author's website. 2: partner website, linked. */
	esc_html__( 'Theme design: %1$s · Urban renewal resources: %2$s', 'futheme' ),
	'<a href="https://www.fudawang.com">福大資訊</a>',
	'<a href="https://www.ur-promoter.com">ur-promoter.com</a>'
);
?>
<!-- wp:group {"tagName":"footer","align":"full","className":"fu-site-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"},"elements":{"link":{"color":{"text":"var:preset|color|primary-contrast"}},"heading":{"color":{"text":"var:preset|color|primary-contrast"}}}},"backgroundColor":"secondary","textColor":"primary-contrast","layout":{"type":"constrained"}} -->
<footer class="wp-block-group alignfull fu-site-footer has-primary-contrast-color has-secondary-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:site-title {"level":0} /-->

<!-- wp:site-tagline {"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:navigation {"overlayMenu":"never","fontSize":"small","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"fu-site-footer__bottom","style":{"spacing":{"padding":{"top":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide fu-site-footer__bottom" style="padding-top:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"fu-copyright","fontSize":"small"} -->
<p class="fu-copyright has-small-font-size">© <?php echo esc_html( wp_date( 'Y' ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:site-title {"level":0,"isLink":false,"style":{"typography":{"fontWeight":"400"}},"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"fu-credit","fontSize":"small"} -->
<p class="fu-credit has-small-font-size"><?php echo wp_kses_post( $futheme_credit ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></footer>
<!-- /wp:group -->
