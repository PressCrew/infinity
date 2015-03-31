<?php
/**
 * Infinity Theme: BuddyPress navigation
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson & CUNY Academic Commons
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

/**
 * Add Activity Tabs on the Stream Directory
 */
function infinity_bp_sidebar_activity_tabs()
{
	if ( bp_is_activity_component() && bp_is_directory() ):
		infinity_get_template_part( 'templates/parts/vertnav-activity' );
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
		infinity_get_template_part( 'templates/parts/vertnav-group' );
	endif;
}
add_action( 'open_sidebar', 'infinity_bp_sidebar_group_navigation' );

/**
 * Add Member Navigation to Member Pages
 */
function infinity_bp_sidebar_member_navigation()
{
	if ( bp_is_user() ) :
		infinity_get_template_part( 'templates/parts/vertnav-member' );
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