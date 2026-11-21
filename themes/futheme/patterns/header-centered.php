<?php
/**
 * Title: Header: centered
 * Slug: futheme/header-centered
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Logo and site title centered, menu below. Suits culture, education and non-profit sites.
 *
 * @package futheme
 */

?>
<!-- wp:group {"tagName":"header","align":"full","className":"fu-site-header fu-site-header--centered","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"top":{"color":"var:preset|color|primary","width":"4px"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<header class="wp-block-group alignfull fu-site-header fu-site-header--centered has-base-background-color has-background" style="border-top-color:var(--wp--preset--color--primary);border-top-width:4px;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:site-logo {"width":44} /-->

<!-- wp:site-title {"level":0} /--></div>
<!-- /wp:group -->

<!-- wp:navigation {"overlayBackgroundColor":"base","overlayTextColor":"contrast","style":{"typography":{"fontWeight":"500"}},"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} /--></div>
<!-- /wp:group --></header>
<!-- /wp:group -->
