<?php
/**
 * PIE API: feature extensions, BuddyPress support feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage features
 * @since 1.0
 */

/**
 * BuddyPress support feature
 *
 * @package PIE-extensions
 * @subpackage features
 */
class Pie_Easy_Exts_Features_Bp_Support
	extends Pie_Easy_Features_Feature
{
	/**
	 */
	public function init()
	{
		// run parent init method
		parent::init();

		// register sidebars
		$this->register_sidebars();
		
		// extra activity entry links
		add_action( 'bp_activity_entry_meta', array($this,'activity_entry_meta') );
	}

	/**
	 * Register special BuddyPress sidebars
	 */
	public function register_sidebars()
	{
		// activity sidebar
		infinity_base_register_sidebar(
			'activity-sidebar',
			'Activity Sidebar',
			'The Activity widget area'
		);
		// member sidebar
		infinity_base_register_sidebar(
			'member-sidebar',
			'Member Sidebar',
			'The Members widget area'
		);
		// blogs sidebar
		infinity_base_register_sidebar(
			'blogs-sidebar',
			'Blogs Sidebar',
			'The Blogs Sidebar area'
		);
		// groups sidebar
		infinity_base_register_sidebar(
			'groups-sidebar',
			'Groups Sidebar',
			'The Groups widget area'
		);
		// forums sidebar
		infinity_base_register_sidebar(
			'forums-sidebar',
			'Forums Sidebar',
			'The Forums widget area'
		);
	}

	/**
	 * Add cool buttons to activity stream items
	 */
	public function activity_entry_meta()
	{
		// activity object name and type
		$name = bp_get_activity_object_name();
		$type = bp_get_activity_type();

		// check name/type and render link if applicable
		if ( $name == 'blogs' && $type == 'new_blog_post' ) {
			$this->render_activity_entry_link( 'view-post', __( 'View Blog Post', pie_easy_text_domain ) );
		} elseif ( $name == 'blogs' && $type == 'new_blog_comment' ) {
			$this->render_activity_entry_link( 'view-post', __( 'View Blog Comment', pie_easy_text_domain ) );
		} elseif ( $name == 'activity' && $type == 'activity_update' ) {
			$this->render_activity_entry_link( 'view-post', __( 'View Activity Status', pie_easy_text_domain ) );
		} elseif ( $name == 'groups' && $type == 'new_forum_topic' ) {
			$this->render_activity_entry_link( 'view-thread', __( 'View Forum Thread', pie_easy_text_domain ) );
		} elseif ( $name == 'groups' && $type == 'new_forum_post' ) {
			$this->render_activity_entry_link( 'view-post', __( 'View Forum Reply', pie_easy_text_domain ) );
		}
	}

	/**
	 * Render a special activity entry anchor
	 *
	 * @param string $class
	 * @param string $title
	 */
	protected function render_activity_entry_link( $class, $title )
	{
		// render the link ?>
		<a class="<?php print esc_attr( $class ) ?> button" href="<?php bp_activity_thread_permalink() ?>"><?php print $title ?></a><?php
	}
}

?>
