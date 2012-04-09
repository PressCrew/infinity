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
	echo infinity_option_get( 'infinity-core-options-google-analytics' );
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
	// determine the image path
	if ( infinity_option_get( 'infinity-core-options-custom-favicon' ) ) {
		$image = infinity_option_image_url( 'infinity-core-options-custom_favicon', full );
	} else {
		$image = get_template_directory_uri() . '/assets/images/favicon.png';
	}

	// render favicon link ?>
	<link rel="shortcut icon" type="image/png" href="<?php print $image ?>" /><?php
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
	print infinity_get_content_class();
}
add_action( 'content_class', 'infinity_content_class' );

/**
 * Get content class
 * 
 * @return string
 */
function infinity_get_content_class()
{
	// must support grid style feature
	if ( current_theme_supports( 'infinity-grid-style' ) ) {
		return infinity_get_sidebar_size() . ' alpha';
	}
}

/**
 * Sidebar Class: Apply Sidebar Grid Class based on Core Theme Options
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_sidebar_class()
{
	print infinity_get_sidebar_class();
}
add_action( 'sidebar_class', 'infinity_sidebar_class' );

/**
 * Get content class
 *
 * @return string
 */
function infinity_get_sidebar_class()
{
	// must support grid style feature
	if ( current_theme_supports( 'infinity-grid-style' ) ) {

		$size = infinity_get_sidebar_size();
		$suffix = ' omega';

		switch ( $size ) {
			case 'grid_20';
				return 'grid_4' . $suffix;
			case 'grid_19';
				return 'grid_5' . $suffix;
			case 'grid_18';
				return 'grid_6' . $suffix;
			case 'grid_17';
				return 'grid_7' . $suffix;
			case 'grid_16';
				return 'grid_8' . $suffix;
			case 'grid_15';
				return 'grid_9' . $suffix;
			case 'grid_14';
				return 'grid_10' . $suffix;
			case 'grid_13';
				return 'grid_11' . $suffix;
			case 'grid_12';
				return 'grid_12' . $suffix;
		}
	}

	return null;
}

/**
 * Get sidebar size
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_get_sidebar_size()
{
	// must support grid style feature
	if ( current_theme_supports( 'infinity-grid-style' ) ) {
		// get sidebar size
		$size = infinity_option_get( 'infinity-core-options-sidebar-size' );
		// did we get one?
		if ( $size ) {
			return trim( $size );
		}
	}

	return null;
}

/**
 * Sidebar Class: Still Apply Grid Classes with jQuery even if content_class and sidebar_class do actions
 * Are not added to custom templates (Most notably BuddyPress)
 * 
 * @package Infinity
 * @subpackage base
 */
function infinity_grid_fallback()
{
	if ( current_theme_supports( 'infinity-grid-style' ) ) {
		// grab sidebar size
		$size = infinity_get_sidebar_size();
		// get one?
		if ( $size ):
			// render script element ?>
			<script>
				jQuery(document).ready(function() {
					jQuery('#content').addClass('<?php print $size ?>');
				});
			</script><?php
		endif;
	}
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
	if ( current_theme_supports( 'infinity-grid-style' ) ) {
		print 'grid_8';
	}
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
	if (
		current_theme_supports( 'infinity-grid-style' ) &&
		infinity_option_get( 'infinity-core-options-sidebar-position' ) != 'right'
	) {
		print ' sidebar-left';
	}
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
	if (
		current_theme_supports( 'infinity-grid-style' ) &&
		infinity_option_get( 'infinity-core-options-sidebar-position' ) != 'right'
	) {
		// render grid fallback script ?>
		<script>
		jQuery(document).ready(function() {
			jQuery('#content').addClass('sidebar-left');
		});
		</script><?php
	}
}
add_action( 'open_body', 'infinity_sidebar_left_fallback' );
?>
