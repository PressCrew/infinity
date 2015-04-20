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
	echo infinity_option_get( 'core:google-analytics' );
}
add_action( 'close_body', 'infinity_google_analytics' );

/**
 * Render tour feature markup.
 */
function infinity_buddypress_tour()
{
	if (
		true === bp_is_activity_component() &&
		false === bp_is_user() &&
		true === is_user_logged_in()
	) {
		infinity_feature( 'activity', 'bp-tour' );
	}
}
add_action( 'close_body', 'infinity_buddypress_tour' );

/**
 * Custom Favicon
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_custom_favicon()
{
	// determine the image path
	if ( infinity_option_get( 'core:custom-favicon' ) ) {
		// get the image url
		$image = infinity_option_image_url( 'core:custom-favicon', 'full' );
		// render favicon link ?>
		<link rel="shortcut icon" type="image/png" href="<?php print $image ?>" /><?php
	}
}
add_action( 'wp_head', 'infinity_custom_favicon' );

/**
 * Content Class: Let user pick a sidebar and content width through a theme options
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_content_class()
{
	print 'column ' . infinity_get_content_class();
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
	if ( current_theme_supports( 'infinity:wp', 'support' ) ) {
		return infinity_get_sidebar_size();
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
	print 'column ' . infinity_get_sidebar_class();
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
	if ( current_theme_supports( 'infinity:wp', 'support' ) ) {

		$size = infinity_get_sidebar_size();

		switch ( $size ) {
			case 'fourteen';
				return 'two';
			case 'thirteen';
				return 'three';
			case 'twelve';
				return 'four';
			case 'eleven';
				return 'five';
			case 'ten';
				return 'six';
			case 'nine';
				return 'seven';
			case 'eight';
				return 'eight';
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
	if ( current_theme_supports( 'infinity:wp', 'support' ) ) {
		// get sidebar size
		$size = infinity_option_get( 'core:sidebar-size' );
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
	if ( current_theme_supports( 'infinity:wp', 'support' ) ) {
		// grab sidebar size
		$size = infinity_get_sidebar_size();
		// get one?
		if ( $size ):
			// render script element ?>
			<script type="text/javascript">
			//<![CDATA[
				jQuery(document).ready(function($) {
					$('#content').addClass('column <?php print $size ?>');
				});
			//]]>
			</script><?php
		endif;
	}
}
add_action( 'open_body', 'infinity_grid_fallback' );
	
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
		current_theme_supports( 'infinity:wp', 'support' ) &&
		infinity_option_get( 'core:sidebar-position' ) != 'right'
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
		current_theme_supports( 'infinity:wp', 'support' ) &&
		infinity_option_get( 'core:sidebar-position' ) != 'right'
	) {
		// render grid fallback script ?>
		<script type="text/javascript">
		//<![CDATA[
			jQuery(document).ready(function($) {
				$('#content').addClass('sidebar-left');
			});
		//]]>
		</script><?php
	}
}
add_action( 'open_body', 'infinity_sidebar_left_fallback' );
