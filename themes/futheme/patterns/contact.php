<?php
/**
 * Title: Contact section
 * Slug: futheme/contact
 * Categories: futheme, call-to-action
 * Description: Primary-colour call to action with contact details and a button. Replace the sample details with your own; the theme does not include a form or store any data.
 * Keywords: contact, call to action, phone, email
 *
 * @package futheme
 */

?>
<!-- wp:group {"align":"full","className":"fu-contact","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|60"},"elements":{"link":{"color":{"text":"var:preset|color|primary-contrast"}},"heading":{"color":{"text":"var:preset|color|primary-contrast"}}}},"backgroundColor":"primary","textColor":"primary-contrast","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull fu-contact has-primary-contrast-color has-primary-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Get in touch', 'futheme' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'Tell visitors how to reach you and when to expect a reply.', 'futheme' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"14rem"}} -->
<div class="wp-block-group alignwide">
<?php
foreach (
	array(
		array( __( 'Phone', 'futheme' ), __( 'Add your phone number', 'futheme' ) ),
		array( __( 'Email', 'futheme' ), __( 'Add your email address', 'futheme' ) ),
		array( __( 'Address', 'futheme' ), __( 'Add your address or office hours', 'futheme' ) ),
	) as $futheme_contact
) :
	?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size"><?php echo esc_html( $futheme_contact[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $futheme_contact[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
	<?php
endforeach;
?>
</div>
<!-- /wp:group -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary-contrast","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-primary-contrast-background-color has-text-color has-background wp-element-button" href="#"><?php esc_html_e( 'Contact us', 'futheme' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
