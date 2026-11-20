<?php
/**
 * Title: Footer: three columns
 * Slug: futheme/footer-columns
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Site title, menu and contact details in three columns, with copyright and theme credit.
 *
 * @package futheme
 */

/* translators: %s: theme author name, linked to the author's website. */
$futheme_credit = sprintf( esc_html__( 'Theme design: %s', 'futheme' ), '<a href="https://www.fudawang.com">福大資訊</a>' );
?>
<!-- wp:group {"tagName":"footer","align":"full","className":"fu-site-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|60"},"elements":{"link":{"color":{"text":"var:preset|color|primary-contrast"}},"heading":{"color":{"text":"var:preset|color|primary-contrast"}}}},"backgroundColor":"secondary","textColor":"primary-contrast","layout":{"type":"constrained"}} -->
<footer class="wp-block-group alignfull fu-site-footer has-primary-contrast-color has-secondary-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:group {"align":"wide","layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"14rem"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:site-title {"level":0} /-->

<!-- wp:site-tagline {"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"medium"} -->
<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Links', 'futheme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:navigation {"overlayMenu":"never","fontSize":"small","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":2,"fontSize":"medium"} -->
<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Contact', 'futheme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php esc_html_e( 'Add your phone number', 'futheme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php esc_html_e( 'Add your email address', 'futheme' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size"><?php esc_html_e( 'Add your address or office hours', 'futheme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
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
