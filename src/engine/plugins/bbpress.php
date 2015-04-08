<?php
/**
 * Infinity Theme: bbPress compat
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

//
// Filters
//

/**
 * Prepend our custom bbPress templates location to the template stack.
 *
 * @param array $stack
 * @return array
 */
function infinity_bbpress_add_template_stack_locations_filter( $stack )
{
	// prepend our custom template location to the stack
	array_unshift( $stack, INFINITY_THEME_PATH . '/templates/plugins/bbpress' );

	// return modified stack
	return $stack;
}
add_filter( 'bbp_add_template_stack_locations', 'infinity_bbpress_add_template_stack_locations_filter', 99 );

//
// Helpers
//

/**
 * Retuns true if on any bbPress page.
 *
 * @return bool
 */
function infinity_bbpress_is_page()
{
	return (
		true === function_exists( 'is_bbpress' ) &&
		true === is_bbpress()
	);
}

/**
 * Render new topic button.
 *
 * @uses bp_button() to render button html.
 */
function infinity_bbpress_new_topic_button()
{
	// check some conditions
	if (
		// don't show in sidebar
		false === (bool) did_action( 'open_sidebar' ) &&
		// bp button helper function must exist
		true === function_exists( 'bp_button' )
	) {
		// good to go, render the button
		bp_button( array(
			'id'                => 'new_topic',
			'component'         => 'groups',
			'must_be_logged_in' => true,
			'block_self'        => true,
			'wrapper_class'     => 'group-button',
			'link_href'         => '#new-post',    // anchor modified
			'link_class'        => 'group-button', // removed a link_class here
			'link_id'           => 'new-topic-button',
			'link_text'         => __( 'New Topic', 'buddypress' ),
			'link_title'        => __( 'New Topic', 'buddypress' ),
		));
	}
}

//
// Compat
//

/**
 * Custom version of bp_is_group_forum() that works with bbPress >= v2.
 *
 * @return boolean
 */
function infinity_bbpress_compat_is_group_forum()
{
	return (
		bp_is_single_item() &&
		bp_is_groups_component() &&
		bp_is_current_action( 'forum' )
	);
}

/**
 * Fix the "New Topic" button when using bbPress with BP.
 *
 * @todo Remove this when bbPress addresses this.
 */
function infinity_bbpress_compat_new_topic_button()
{
	// do all of these conditions exist?
	if (
		// bp is loaded and func exists?
		true === function_exists( 'bp_is_active' ) &&
		// groups component is active?
		true === bp_is_active( 'groups' ) &&
		// user is logged in?
		true === is_user_logged_in() &&
		// current screen is a group forum?
		true === infinity_bbpress_compat_is_group_forum() &&
		// current screen is NOT a group forum topic
		false === bp_is_group_forum_topic() &&
		// logged in user is not banned?
		false === bp_group_is_user_banned()
	) {
		// yes, first remove the 'New Topic' button.
		// this has to be done because the 'bp_get_group_new_topic_button' filter doesn't work properly.
		remove_action( 'bp_group_header_actions', 'bp_group_new_topic_button' );
		// now, add our customized 'New Topic' button
		add_action( 'bp_group_header_actions', 'infinity_bbpress_new_topic_button' );
	}
}
add_action( 'bp_actions', 'infinity_bbpress_compat_new_topic_button' );