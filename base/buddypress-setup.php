<?php
/**
 * Infinity Theme: BuddyPress setup
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.0
 */

#BuddyPress specific Sidebars.
register_sidebar(array(
'name' => 'Home Sidebar',
'id' => 'activity-sidebar',
'description' => "The Home Sidebar",
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>'
));

register_sidebar(array(
'name' => 'Activity Sidebar',
'id' => 'activity-sidebar',
'description' => "The Activity Sidebar area",
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>'
));
 
register_sidebar(array(
'name' => 'Member Sidebar',
'id' => 'member-sidebar',
'description' => "The Members widget area",
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>'
));

register_sidebar(array(
'name' => 'Blogs Sidebar',
'id' => 'blogs-sidebar',
'description' => "The Blogs Sidebar area",
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>'
));
 
register_sidebar(array(
'name' => 'Groups Sidebar',
'id' => 'groups-sidebar',
'description' => "The Groups widget area",
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>'
));
 
register_sidebar(array(
'name' => 'Forums Sidebar',
'id' => 'forums-sidebar',
'description' => "The Forums widget area",
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>'
));

function bp_message_notices() { { ?>
<!-- html -->
<div class="top-notification-box">
	<?php if ( is_user_logged_in() && bp_is_active( 'messages' ) ) : ?>
				<?php bp_message_get_notices(); /* Site wide notices to all users */ ?>
	<?php endif; ?>
</div>
<!-- end -->
<?php }} 
// Hook into action
add_action('open_sidebar','bp_message_notices');

// ======================================================================== 
// ! Start BuddyPress Setup. This is based on the BP-Template Pack Plugin   
// ======================================================================== 
/**
 * Sets up WordPress theme for BuddyPress support.
 *
 * @since 1.2
 */
function infinity_bp_theme_setup() {
	global $bp;
		require_once( BP_PLUGIN_DIR . '/bp-themes/bp-default/_inc/ajax.php' );

	if ( !is_admin() ) {
		// Register buttons for the relevant component templates
		// Friends button
		if ( bp_is_active( 'friends' ) )
			add_action( 'bp_member_header_actions',    'bp_add_friend_button' );

		// Activity button
		if ( bp_is_active( 'activity' ) )
			add_action( 'bp_member_header_actions',    'bp_send_public_message_button' );

		// Messages button
		if ( bp_is_active( 'messages' ) )
			add_action( 'bp_member_header_actions',    'bp_send_private_message_button' );

		// Group buttons
		if ( bp_is_active( 'groups' ) ) {
			add_action( 'bp_group_header_actions',     'bp_group_join_button' );
			add_action( 'bp_group_header_actions',     'bp_group_new_topic_button' );
			add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
		}

		// Blog button
		if ( bp_is_active( 'blogs' ) )
			add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
	}
}
add_action( 'after_setup_theme', 'infinity_bp_theme_setup', 11 );

/**
 * Enqueues BuddyPress JS and related AJAX functions
 *
 * @since 1.2
 */
function infinity_bp_enqueue_scripts() {
	// Do not enqueue JS if it's disabled
	if ( get_option( 'infinity_bp_disable_js' ) )
		return;

	// Add words that we need to use in JS to the end of the page so they can be translated and still used.
	$params = array(
		'my_favs'           => __( 'My Favorites', 'buddypress' ),
		'accepted'          => __( 'Accepted', 'buddypress' ),
		'rejected'          => __( 'Rejected', 'buddypress' ),
		'show_all_comments' => __( 'Show all comments for this thread', 'buddypress' ),
		'show_all'          => __( 'Show all', 'buddypress' ),
		'comments'          => __( 'comments', 'buddypress' ),
		'close'             => __( 'Close', 'buddypress' )
	);

	// BP 1.5+
	if ( version_compare( BP_VERSION, '1.3', '>' ) ) {
		// Bump this when changes are made to bust cache
		$version            = '20110818';

		$params['view']     = __( 'View', 'buddypress' );
	}
	// BP 1.2.x
	else {
		$version = '20110729';

		if ( bp_displayed_user_id() )
			$params['mention_explain'] = sprintf( __( "%s is a unique identifier for %s that you can type into any message on this site. %s will be sent a notification and a link to your message any time you use it.", 'buddypress' ), '@' . bp_get_displayed_user_username(), bp_get_user_firstname( bp_get_displayed_user_fullname() ), bp_get_user_firstname( bp_get_displayed_user_fullname() ) );
	}

	// Enqueue the global JS - Ajax will not work without it
	wp_enqueue_script( 'dtheme-ajax-js', BP_PLUGIN_URL . '/bp-themes/bp-default/_inc/global.js', array( 'jquery' ), $version );

	// Localize the JS strings
	wp_localize_script( 'dtheme-ajax-js', 'BP_DTheme', $params );
}
add_action( 'wp_enqueue_scripts', 'infinity_bp_enqueue_scripts' );

if ( !function_exists( 'infinity_bp_use_wplogin' ) ) :
/**
 * BP Template Pack doesn't use bp-default's built-in sidebar login block,
 * so during no access requests, we need to redirect them to wp-login for
 * authentication.
 *
 * @since 1.2
 */
function infinity_bp_use_wplogin() {
	// returning 2 will automatically use wp-login
	return 2;
}
add_filter( 'bp_no_access_mode', 'infinity_bp_use_wplogin' );
endif;

/**
 * Hooks into the 'bp_get_activity_action_pre_meta' action to add secondary activity avatar support
 *
 * @since 1.2
 */
function infinity_bp_activity_secondary_avatars( $action, $activity ) {
	// sanity check - some older versions of BP do not utilize secondary activity avatars
	if ( function_exists( 'bp_get_activity_secondary_avatar' ) ) :
		switch ( $activity->component ) {
			case 'groups' :
			case 'friends' :
				// Only insert avatar if one exists
				if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
					$reverse_content = strrev( $action );
					$position        = strpos( $reverse_content, 'a<' );
					$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
				}
				break;
		}
	endif;

	return $action;
}
add_filter( 'bp_get_activity_action_pre_meta', 'infinity_bp_activity_secondary_avatars', 10, 2 );
?>