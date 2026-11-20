<?php
/**
 * Title: Header: with button
 * Slug: futheme/header-cta
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Menu plus a call-to-action button. Suits business and service sites; change the button link to your contact page.
 *
 * @package futheme
 */

?>
<!-- wp:group {"tagName":"header","align":"full","className":"fu-site-header fu-site-header--cta","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"top":{"color":"var:preset|color|primary","width":"4px"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<header class="wp-block-group alignfull fu-site-header fu-site-header--cta has-base-background-color has-background" style="border-top-color:var(--wp--preset--color--primary);border-top-width:4px;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:site-logo {"width":44} /-->

<!-- wp:site-title {"level":0} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"right"}} -->
<div class="wp-block-group"><!-- wp:navigation {"overlayBackgroundColor":"base","overlayTextColor":"contrast","style":{"typography":{"fontWeight":"500"}},"layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"}} /-->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"fontSize":"small"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-small-font-size has-custom-font-size wp-element-button" href="#"><?php esc_html_e( 'Contact us', 'futheme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></header>
<!-- /wp:group -->
