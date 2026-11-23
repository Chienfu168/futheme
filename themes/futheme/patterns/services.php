<?php
/**
 * Title: Service cards
 * Slug: futheme/services
 * Categories: futheme, featured
 * Description: Section heading with three service cards in a responsive grid. Replace the sample text with your own services.
 * Keywords: services, cards, features
 *
 * @package futheme
 */

?>
<!-- wp:group {"align":"full","className":"fu-services","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull fu-services" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Our services', 'futheme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"muted"} -->
<p class="has-text-align-center has-muted-color has-text-color"><?php esc_html_e( 'A short sentence that explains what you offer and who it is for.', 'futheme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16rem"}} -->
<div class="wp-block-group alignwide">
<?php
foreach (
	array(
		array( __( 'First service', 'futheme' ), __( 'Describe the first service in one or two sentences.', 'futheme' ) ),
		array( __( 'Second service', 'futheme' ), __( 'Describe the second service in one or two sentences.', 'futheme' ) ),
		array( __( 'Third service', 'futheme' ), __( 'Describe the third service in one or two sentences.', 'futheme' ) ),
	) as $futheme_service
) :
	?>
<!-- wp:group {"className":"fu-card","style":{"border":{"radius":"0.375rem","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|30"}},"backgroundColor":"surface","borderColor":"border","layout":{"type":"flex","orientation":"vertical","verticalAlignment":"top"}} -->
<div class="wp-block-group fu-card has-border-color has-border-border-color has-surface-background-color has-background" style="border-width:1px;border-radius:0.375rem;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $futheme_service[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $futheme_service[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
	<?php
endforeach;
?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
