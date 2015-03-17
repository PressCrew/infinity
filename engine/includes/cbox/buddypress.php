<?php
/**
 * Commons In A Box Theme: BuddyPress setup
 */

/**
 * Temporarily fix the "New Topic" button when using bbPress with BP.
 *
 * @todo Remove this when bbPress addresses this.
 */
function cbox_fix_bbp_new_topic_button() {
	// if groups isn't active, stop now!
	if ( ! bp_is_active( 'groups' ) )
		return;

	// if bbPress 2 isn't enabled, stop now!
	if ( ! function_exists( 'bbpress' ) )
		return;

	// remove the 'New Topic' button
	// this is done because the 'bp_get_group_new_topic_button' filter doesn't
	// work properly
	remove_action( 'bp_group_header_actions', 'bp_group_new_topic_button' );

	// version of bp_is_group_forum() that works with bbPress 2
	$is_group_forum = bp_is_single_item() && bp_is_groups_component() && bp_is_current_action( 'forum' );

	// If these conditions are met, this button should not be displayed
	if ( ! is_user_logged_in() || ! $is_group_forum || bp_is_group_forum_topic()|| bp_group_is_user_banned() )
		return false;

	// create function to output new topic button
	$new_button = create_function( '', "
		// do not show in sidebar
		if ( did_action( 'open_sidebar' ) )
			return;

		// render the button
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
		) );
	" );

	// add our customized 'New Topic' button
	add_action( 'bp_group_header_actions', $new_button );

}
add_action( 'bp_actions', 'cbox_fix_bbp_new_topic_button' );

/**
 * When running BuddyPress Docs, don't allow theme compatibility mode to kick in
 *
 * @since 1.0.5
 */
add_filter( 'bp_docs_do_theme_compat', '__return_false' );
