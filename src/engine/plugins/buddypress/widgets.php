<?php
/**
 * Infinity Theme: BuddyPress widgets
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
 * Register custom BP widgets
 */
function infinity_bp_register_widgets()
{
	// register it
	if ( bp_is_active( 'blogs' ) ) {
		register_widget( "Infinity_BP_Blogs_Recent_Posts_Widget" );
	}
}
add_action( 'widgets_init', 'infinity_bp_register_widgets' );

//
// Custom Widget Classes
//

/**
 * Recent Networkwide Blog Posts Widget
 */
class Infinity_BP_Blogs_Recent_Posts_Widget extends WP_Widget
{
	/**
	 */
	function __construct()
	{
		// define widget options
		$widget_ops = array(
			'classname' => 'widget_' . strtolower( __CLASS__ ) . ' buddypress',
		);
		// run parent
		parent::__construct( false, $name = __( 'Recent Networkwide Blog Posts', 'infinity-engine' ), $widget_ops );
	}

	/**
	 */
	function widget( $args, $instance )
	{
		// init vars to be extracted
		$before_widget =
		$before_title =
		$after_title =
		$after_widget = '';

		// extract args
		extract( $args );

		echo $before_widget;
		echo $before_title;

		if ( false === empty( $instance['link_title'] ) ) {
			$dir_link = trailingslashit( bp_get_root_domain() ) . trailingslashit( bp_get_blogs_root_slug() );
			// render title link ?>
			<a href="<?php echo esc_attr( $dir_link ) ?>"><?php echo esc_html( $instance['title'] ) ?></a><?php
		} else {
			// just spit out title escaped
			echo esc_html( $instance['title'] );
		}

		echo $after_title;

		// is max posts set?
		if ( true === empty( $instance['max_posts'] ) ) {
			// no, set a default
			$instance['max_posts'] = 10;
		}

		// load more items that we need, because many will be filtered out by privacy
		$real_max = $instance['max_posts'] * 10;
		$counter = 0;
		
		// set the default action
		$action = 'new_blog_post';
		
		// also include new group blog post action?
		if ( false === empty( $instance['include_groupblog'] ) ) {
			// yes, append it to action string
			$action .= ',new_groupblog_post';
		}

		// start bp activities loop
		$bp_activities = bp_has_activities(
			'action=' . $action .
			'&max=' . $real_max .
			'&per_page=' . $real_max
		);

		// have any activities to list?
		if ( $bp_activities ):

			// yes, open the list ?>
			<ul id="blog-post-list" class="activity-list item-list"><?php

				// loop all activities
				while ( bp_activities() ):

					// setup this activity
					bp_the_activity();

					// is counter at max posts?
					if ( $counter++ >= $instance['max_posts'] ):
						// yes, break loop
						break;
					endif;

					// render list item ?>
					<li>
						<div class="activity-content" style="margin: 0">
							<div class="activity-avatar">
								<?php bp_activity_avatar() ?>
							</div>
							<div class="activity-header">
								<?php bp_activity_action() ?>
							</div>
							<?php
								if ( bp_get_activity_content_body() ):
									bp_activity_content_body();
								endif;
							?>
						</div>
					</li><?php

				endwhile;

			// close the list ?>
			</ul>

			<p class="cac-more-link">
				<a href="<?php bp_blogs_directory_permalink(); ?>"><?php _e( 'More Blogs', 'infinity-engine' ) ?></a>
			</p><?php

		else:

			// no blog posts found ?>
			<div id="message" class="info">
				<p>
					<?php _e( 'Sorry, there were no blog posts found. Why not write one?', 'infinity-engine' ) ?>
				</p>
			</div><?php

		endif;

		echo $after_widget;
	}

	/**
	 */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['max_posts']  = strip_tags( $new_instance['max_posts'] );
		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['link_title'] = empty( $new_instance['link_title'] ) ? '0' : '1';
		$instance['include_groupblog'] = empty( $new_instance['include_groupblog'] ) ? '0' : '1';

		return $instance;
	}

	/**
	 */
	function form( $instance )
	{
		// parse instance to get final settings
		$settings = wp_parse_args(
			(array) $instance,
			array(
				'max_posts'  => 10,
				'title'      => __( 'Recent Blog Posts', 'infinity-engine' ),
				'link_title' => true,
			)
		);

		$max_posts  = strip_tags( $settings['max_posts'] );
		$title      = strip_tags( $settings['title'] );
		$link_title = (bool) $settings['link_title'];
		$include_groupblog = (bool) $instance['include_groupblog'];

		// render the form fields ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ) ?>">
				<?php _e( 'Title: ', 'infinity-engine' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 90%" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'link_title' ) ?>">
				<?php _e( 'Link widget title to Blogs directory:', 'infinity-engine' ) ?>
			</label>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'link_title' ) ?>" value="1" <?php checked( $link_title ) ?>>
		</p>
		<?php
			// display group blog checkbox?
			if (
				true === is_multisite() &&
				true === bp_is_active( 'groups' ) &&
				true === defined( 'BP_GROUPBLOG_IS_INSTALLED' )
			):
		?>
			<p>
				<label for="<?php echo $this->get_field_name( 'include_groupblog' ) ?>">
					<?php _e( 'Include groupblog posts', 'infinity-engine' ) ?>
				</label>
				<input type="checkbox" name="<?php echo $this->get_field_name( 'include_groupblog' ) ?>" value="1" <?php checked( $include_groupblog ) ?>>
			</p>
		<?php
			endif;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'max_posts' ) ?>">
				<?php _e( 'Max posts to show:', 'infinity-engine' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'max_posts' ); ?>" name="<?php echo $this->get_field_name( 'max_posts' ); ?>" type="text" value="<?php echo esc_attr( $max_posts ); ?>" style="width: 30%" />
		</p><?php
	}
}
