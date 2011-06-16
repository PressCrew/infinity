<?php
/**
 * Infinity Theme: BuddyPress setup
 *
 * @author Bowe Frankema <bowromir@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package infinity
 * @subpackage includes
 * @since 1.0
 */

register_sidebar( array(
	'name' => 'Activity Sidebar',
	'id' => 'activity-sidebar',
	'description' => "The Activity widget area",
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));
 
register_sidebar( array(
	'name' => 'Member Sidebar',
	'id' => 'member-sidebar',
	'description' => "The Members widget area",
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));

register_sidebar( array(
	'name' => 'Blogs Sidebar',
	'id' => 'blogs-sidebar',
	'description' => "The Blogs Sidebar area",
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));
 
register_sidebar( array(
	'name' => 'Groups Sidebar',
	'id' => 'groups-sidebar',
	'description' => "The Groups widget area",
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));
 
register_sidebar( array(
	'name' => 'Forums Sidebar',
	'id' => 'forums-sidebar',
	'description' => "The Forums widget area",
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>'
));
	
/**
 * Disable custom header from the bp-default theme and use the awesome Infinity one instead.
 */
define( 'BP_DTHEME_DISABLE_CUSTOM_HEADER', true );	

/*
 * Add Cool Buttons to Activity Stream Items
 */
function infinity_base_bp_activity_entry_meta()
{
	$class = 'view-post';
	$text = '';

	switch ( bp_get_activity_object_name() ) {
		case 'activity':
			switch ( bp_get_activity_type() ) {
				case 'activity_update':
					$text = __( 'View Activity Status', infinity_text_domain );
					break 2;
				default:
					return false;
			}
		case 'blogs':
			switch ( bp_get_activity_type() ) {
				case 'new_blog_post':
					$text = __( 'View Blog Post', infinity_text_domain );
					break 2;
				case 'new_blog_comment':
					$text = __( 'View Blog Comment', infinity_text_domain );
					break 2;
				default:
					return false;
			}
		case 'groups':
			switch ( bp_get_activity_type() ) {
				case 'new_forum_topic':
					$class = 'view-thread';
					$text = __( 'View Forum Thread', infinity_text_domain );
					break 2;
				case 'new_forum_post':
					$text = __( 'View Forum Reply', infinity_text_domain );
					break 2;
				default:
					return false;
			}
		default:
			return false;
	}
?>
	<a class="<?php print $class ?>" href="<?php bp_activity_thread_permalink() ?>"><?php print $text ?></a><?php
}
add_action( 'bp_activity_entry_meta', 'infinity_base_bp_activity_entry_meta' );

/**
 * Custom default avatar
 *
 * @return string
 */
function infinity_base_bp_core_mysteryman_src()
{
	return infinity_image_url( 'no_photo.jpg' );
}
add_filter( 'bp_core_mysteryman_src', 'infinity_base_bp_core_mysteryman_src' );

/**
 * Custom group avatar
 *
 * @param string $avatar
 * @return string
 */
function infinity_base_bp_get_group_avatar( $avatar )
{
	if ( strpos( $avatar, 'group-avatars' ) ) {

		return $avatar;
		
	} else {
		
		$width = BP_AVATAR_FULL_WIDTH;
		$height = BP_AVATAR_FULL_HEIGHT;
		$avatar = infinity_image_url( 'no_photo.jpg' );

		if ( bp_current_action() == '' ) {
			$width = BP_AVATAR_THUMB_WIDTH;
			$height = BP_AVATAR_THUMB_HEIGHT;
		}
		
		return sprintf(
			'<img width="%d" height="%d" src="%s" class="avatar" alt="%s" />',
			$width, $height, $avatar, esc_attr( bp_get_group_name() )
		);
	}
}
add_filter( 'bp_get_group_avatar', 'infinity_base_bp_get_group_avatar');

?>