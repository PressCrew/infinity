<?php
/**
 * Add Core Options
 *
 * @package Infinity
 * @subpackage base
 * @todo move this to a feature extension, no direct plugin support
 */

/**
 * Insert Google Analyitcs code
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_google_analytics()
{
	// render analytics code
	echo infinity_option_get( 'infinity-core-options-google_analytics' );
} 
add_action('close_body','infinity_google_analytics');

/**
 * Custom Favicon
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_custom_favicon()
{
	// render favicon link ?>
	<?php if ( infinity_option_get( 'infinity-core-options-custom_favicon' ) ): ?>
		<link rel="shortcut icon" type="image/png" href="<?php echo infinity_option_image_url( 'infinity-core-options-custom_favicon', full ); ?>" />
	<?php else: ?>
		<link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png" />
	<?php endif;
}
add_action('wp_head','infinity_custom_favicon'); 

/**
 * Content Class: Let user pick a sidebar and content width through a theme options
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_content_class()
{
	if ( !current_theme_supports( 'infinity-grid-style' ) ) {
		return;
	}
	
	// render class attribute ?>
<?php if ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_17" ): ?>grid_17 alpha
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_20" ): ?>grid_20 alpha
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_19" ): ?>grid_19 alpha
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_18" ): ?>grid_18 alpha
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_16" ): ?>grid_16 alpha
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_15" ): ?>grid_15 alpha
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_14" ): ?>grid_14 alpha
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_12" ): ?>grid_12 alpha
<?php endif;
}
add_action( 'content_class', 'infinity_content_class' );
	
/**
 * Sidebar Class: Apply Sidebar Grid Class based on Core Theme Options
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_sidebar_class()
{
	if ( !current_theme_supports( 'infinity-grid-style' ) ) {
		return;
	}
	
	// render grid class ?>
<?php if ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_17" ): ?>grid_7 omega
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_20" ): ?>grid_4 omega
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_19" ): ?>grid_5 omega
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_18" ): ?>grid_6 omega
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_16" ): ?>grid_8 omega
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_15" ): ?>grid_9 omega
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_14" ): ?>grid_10 omega
<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_12" ): ?>grid_12 omega
<?php endif;
}
add_action( 'sidebar_class', 'infinity_sidebar_class' );
	 
/**
 * Sidebar Class: Still Apply Grid Classes with jQuery even if content_class and sidebar_class do actions
 * Are not added to custom templates (Most notably BuddyPress)
 * 
 * @package Infinity
 * @subpackage base
 */
function infinity_grid_fallback()
{
	if ( !current_theme_supports( 'infinity-grid-style' ) ) {
		return;
	}
	// render grid fallback script ?>
	<script>
	jQuery(document).ready(function() {
	<?php if ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_17" ): ?>
		jQuery('#content').removeClass('grid_17');
		jQuery('#content').addClass('grid_17');
	<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_20" ): ?>
		jQuery('#content').removeClass('grid_20');
		jQuery('#content').addClass('grid_20');
	<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_19" ): ?>
		jQuery('#content').removeClass('grid_19');
		jQuery('#content').addClass('grid_19');
	<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_18" ): ?>
		jQuery('#content').removeClass('grid_18');
		jQuery('#content').addClass('grid_18');
	<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_16" ): ?>
		jQuery('#content').removeClass('grid_16');
		jQuery('#content').addClass('grid_16');
	<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_15" ): ?>
		jQuery('#content').removeClass('grid_15');
		jQuery('#content').addClass('grid_15');
	<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_14" ): ?>
		jQuery('#content').removeClass('grid_14');
		jQuery('#content').addClass('grid_14');
	<?php elseif ( infinity_option_get( 'infinity-core-options-sidebar_size' ) == "grid_12" ): ?>
		jQuery('#content').removeClass('grid_12');
		jQuery('#content').addClass('grid_12');
	<?php endif; ?>
	});
	</script><?php
}
add_action( 'open_body', 'infinity_grid_fallback' );
	
/**
 * Add Footer Widget Class
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_footer_widget_class()
{
	if ( !current_theme_supports( 'infinity-grid-style' ) ) {
		return;
	}
// render grid class
?>grid_8<?php
}
add_action( 'footer_widget_class', 'infinity_footer_widget_class' );

/**
* Add Left Sidebar class to content/sidebar id based on theme option.
*
*
* @package Infinity
* @subpackage base
*/
function infinity_sidebar_position_css()
{
	if ( !current_theme_supports( 'infinity-grid-style' ) || infinity_option_get( 'infinity-core-options-sidebar_position' ) == "right" ) {
		return;
	}
// render grid class
?>sidebar-left<?php
}
add_action( 'sidebar_class', 'infinity_sidebar_position_css' );
add_action( 'content_class', 'infinity_sidebar_position_css' );

/**
 * Left Sidebar Class. Add Left Sidebar class to content when content_class and sidebar_class do actions
 * Are not added to custom templates (Most notably BuddyPress)
 * 
 * @package Infinity
 * @subpackage base
 */
function infinity_sidebar_left_fallback()
{
	if ( !current_theme_supports( 'infinity-grid-style' ) || infinity_option_get( 'infinity-core-options-sidebar_position' ) == "right" ) {
		return;
	}
	// render grid fallback script ?>
	<script>
	jQuery(document).ready(function() {
		jQuery('#content').removeClass('sidebar-left');
		jQuery('#content').addClass('sidebar-left');
	});
	</script><?php
}
add_action( 'open_body', 'infinity_sidebar_left_fallback' );
?>
