<?php
/**
 * Infinity Theme: BuddyPress base functionality
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson & CBOX Team
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

//
// Actions
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

//
// Helpers
//

/**
 * Populate the $groups_template global for use outside the loop
 *
 * We build the group navigation outside the groups loop. In order to use BP's
 * group template functions while building the nav, we must have the template
 * global populated. In this function, we fill in any missing data, based on
 * the current group.
 *
 * This issue should be fixed more elegantly upstream in BuddyPress, ideally
 * by making the template functions fall back on the current group when the
 * loop global is not populated.
 *
 * @see cbox-theme#155
 */
function infinity_bp_populate_group_global()
{
	global $groups_template;

	if (
		true === bp_is_group() &&
		true === isset( $groups_template->groups[0]->group_id ) &&
		true === empty( $groups_template->groups[0]->name )
	) {
		// get the current group
		$current_group = groups_get_current_group();

		// fill in all missing properties
		foreach ( $current_group as $cur_key => $cur_value ) {
			if ( ! isset( $groups_template->groups[0]->{$cur_key} ) ) {
				$groups_template->groups[0]->{$cur_key} = $cur_value;
			}
		}
	}
}

/**
 * Create an excerpt
 *
 * Uses bp_create_excerpt() when available. Otherwise falls back on a very
 * rough approximation, ignoring the fancy params passed.
 *
 * @return string
 */
function infinity_bp_create_excerpt( $text, $length = 425, $options = array() )
{
	// does bp function exist?
	if ( function_exists( 'bp_create_excerpt' ) ) {
		// yes, use it
		return bp_create_excerpt( $text, $length, $options );
	} else {
		// no, wing it
		return substr( $text, 0, $length ) . ' [&hellip;]';
	}
}

//
// Conditionals
//

if ( false == function_exists( 'is_activity_page' ) ) {
	/**
	 * Activity Stream Conditional
	 */
	function is_activity_page() {
		return ( bp_is_activity_component() && !bp_is_user() );
	}
}