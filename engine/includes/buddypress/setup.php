<?php
/**
 * Infinity Theme: BuddyPress global setup
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson & CBOX Team
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.0
 */

/**
 * Change Default Avatar Size
 */
if ( !defined( 'BP_AVATAR_THUMB_WIDTH' ) ) {
	define( 'BP_AVATAR_THUMB_WIDTH', 80 );
}

if ( !defined( 'BP_AVATAR_THUMB_HEIGHT' ) ) {
	define( 'BP_AVATAR_THUMB_HEIGHT', 80 );
}

if ( !defined( 'BP_AVATAR_FULL_WIDTH' ) ) {
	define( 'BP_AVATAR_FULL_WIDTH', 300 );
}

if ( !defined( 'BP_AVATAR_FULL_HEIGHT' ) ) {
	define( 'BP_AVATAR_FULL_HEIGHT', 300 );
}

//
// Template Actions
//

/**
 * Make sure BuddyPress items that are attached to 'bp_head' are added.
 *
 * 'bp_head' is a hook that is hardcoded in bp-default's header.php.  So we
 * add the same hook here attached to the 'wp_head' action if it hasn't
 * already been added.
 *
 * This hook is used by BP to add activity item feeds.  Other plugins like
 * BuddyPress Courseware also uses this hook.
 */
function infinity_bp_head_action()
{
	// was bp_head added yet?
	if ( false === has_action( 'wp_head', 'bp_head' ) ) {
		// no, add it
		add_action( 'wp_head', 'bp_head' );
	}
}
add_action( 'wp_head', 'infinity_bp_head_action', 0 );

/**
 * Add Activity Tabs on the Stream Directory
 */
function infinity_bp_sidebar_activity_tabs()
{
	if ( bp_is_activity_component() && bp_is_directory() ):
		infinity_get_template_part( 'templates/parts/activity-tabs' );
	endif;
}
add_action( 'open_sidebar', 'infinity_bp_sidebar_activity_tabs' );

/**
 * Add Group Navigation Items to Group Pages
 */
function infinity_bp_sidebar_group_navigation()
{
	if ( bp_is_group() ) :
		infinity_bp_populate_group_global();
		infinity_get_template_part( 'templates/parts/group-navigation' );
	endif;
}
add_action( 'open_sidebar', 'infinity_bp_sidebar_group_navigation' );

/**
 * Add Member Navigation to Member Pages
 */
function infinity_bp_sidebar_member_navigation()
{
	if ( bp_is_user() ) :
		infinity_get_template_part( 'templates/parts/member-navigation' );
	endif;
}
add_action( 'open_sidebar', 'infinity_bp_sidebar_member_navigation' );

/**
 * Add a filter for every displayed user navigation item
 */
function infinity_bp_member_navigation_filter_setup()
{
	// call helper function in core
	infinity_bp_nav_inject_options_setup();
}
add_action( 'bp_setup_nav', 'infinity_bp_member_navigation_filter_setup', 999 );

/**
 * Filter the options nav on a user's profile only.
 *
 * We want to remove the options nav on user pages because Infinity does a
 * neat job in nesting child items under the parent nav menu.
 */
function infinity_bp_remove_user_options_nav()
{
	global $bp, $infinity_bp_removed_nav_items;
	
	// init the array of removed items
	$infinity_bp_removed_nav_items = array();

	// loop all nav components
	foreach ( (array) $bp->bp_options_nav as $nav_item ) {

		// get all 'css_id' values as the options nav filter relies on this
		$options_nav = wp_list_pluck( $nav_item, 'css_id' );

		// loop all css_id values we plucked
		foreach ( $options_nav as $options_nav_item ) {
			// we're temporarily saving what is removed so we can reinstate it later
			// @see infinity_bp_reinstate_user_options_nav()
			$infinity_bp_removed_nav_items[] = $options_nav_item;
			// add our filter
			add_filter(
				'bp_get_options_nav_' . $options_nav_item,
				'__return_false'
			);
		}
	}
}
add_action( 'bp_before_member_body', 'infinity_bp_remove_user_options_nav' );

/**
 * Reinstate the options nav on a user's profile.
 *
 * {@link infinity_bp_remove_user_options_nav()} removes the options nav, but we
 * need to reinstate it so {@link infinity_bp_nav_inject_options_filter()}
 * can do its nesting thang in the sidebar.
 *
 * The sidebar gets rendered after the regular options nav, which is why
 * we have to do this.
 */
function infinity_bp_reinstate_user_options_nav()
{
	global $infinity_bp_removed_nav_items;

	// did we remove any nav items?
	if ( false === empty( $infinity_bp_removed_nav_items ) ) {
		// yes, loop all of them
		foreach ( (array) $infinity_bp_removed_nav_items as $options_nav_item ) {
			// remove our filter
			remove_filter(
				'bp_get_options_nav_' . $options_nav_item,
				'__return_false'
			);
		}
	}
}
add_action( 'bp_after_member_body', 'infinity_bp_reinstate_user_options_nav' );