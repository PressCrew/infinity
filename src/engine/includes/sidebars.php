<?php
/**
 * Infinity Theme: sidebars
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.0
 */

//
// Actions
//

/**
 * Registers our widget sidebars.
 *
 * Hooked to 'widgets_init'.
 *
 * @package Infinity
 * @subpackage base
 * @uses current_theme_supports()
 * @uses infinity_base_register_sidebars()
 * @uses infinity_base_register_bp_sidebars()
 */
function infinity_base_widgets_setup()
{
	// sidebars enabled?
	if ( current_theme_supports( 'infinity:sidebar', 'setup' ) ) {
		// yep, register base sidebars
		infinity_base_register_sidebars();
		// BuddyPress sidebars enabled?
		if ( current_theme_supports( 'infinity:bp', 'sidebar-setup' ) ) {
			// yep, register BP sidebars
			infinity_base_register_bp_sidebars();
		}
	}
}
add_action( 'widgets_init', 'infinity_base_widgets_setup' );

/**
 * Populates sidebars throughout the theme.
 *
 * It checks each of the target sidebars, and for each one that is empty, it sets up a number
 * of default widgets. Note that this will not override changes you've made
 * to any of these sidebars, unless you've cleared them out completely.
 *
 * @uses ICE_Widget_Setter
 * @since 1.2
 */
function infinity_sidebars_auto_populate()
{
	// load widget utils
	ICE_Loader::load( 'utils/widget-setter' );

	// Homepage Top Right
	if ( infinity_sidebar_is_empty( 'homepage-top-right' ) ) {
		
		// set widget(s)
		infinity_sidebars_set_welcome( 'homepage-top-right' );
		
		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'homepage-top-right' );
	}

	// Homepage Center Widget
	if ( infinity_sidebar_is_empty( 'homepage-center-widget' ) ) {

		// set widget(s)
		infinity_sidebars_set_bp_recently_active( 'homepage-center-widget' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'homepage-center-widget' );
	}

	// Homepage Left
	if ( infinity_sidebar_is_empty( 'homepage-left' ) ) {

		// set widget(s)
		infinity_sidebars_set_bp_groups( 'homepage-left' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'homepage-left' );
	}

	// Homepage Middle
	if ( infinity_sidebar_is_empty( 'homepage-middle' ) ) {

		// set widget(s)
		infinity_sidebars_set_bp_members( 'homepage-middle' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'homepage-middle' );
	}

	// Homepage Right
	if ( infinity_sidebar_is_empty( 'homepage-right' ) ) {

		// try to set bp blogs widget
		if ( true !== infinity_sidebars_set_bp_recent_posts( 'homepage-right' ) ) {
			// fall back to standard recent posts widget
			infinity_sidebars_set_recent_posts( 'homepage-right' );
		}

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'homepage-right' );
	}

	// Blog Sidebar
	if ( infinity_sidebar_is_empty( 'blog-sidebar' ) ) {

		// try to set bp blogs widget
		if ( true !== infinity_sidebars_set_bp_recent_posts( 'blog-sidebar' ) ) {

			// ok, we'll use these fallback widgets
			infinity_sidebars_set_search( 'blog-sidebar' );
			infinity_sidebars_set_archives( 'blog-sidebar' );
			infinity_sidebars_set_recent_posts( 'blog-sidebar' );

		}

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'blog-sidebar' );
	}

	// Page Sidebar
	if ( infinity_sidebar_is_empty( 'page-sidebar' ) ) {

		// set widget(s)
		infinity_sidebars_set_search( 'page-sidebar' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'page-sidebar' );
	}

	// Footer Left
	if ( infinity_sidebar_is_empty( 'footer-left' ) ) {

		// set widget(s)
		infinity_sidebars_set_contact( 'footer-left' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'footer-left' );
	}

	// Footer Middle
	if ( infinity_sidebar_is_empty( 'footer-middle' ) ) {

		// set widget(s)
		infinity_sidebars_set_contact( 'footer-middle' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'footer-middle' );
	}

	// Footer Right
	if ( infinity_sidebar_is_empty( 'footer-right' ) ) {

		// set widget(s)
		infinity_sidebars_set_pages(
			'footer-right',
			array(
				'title' => __( 'Sitemap', 'infinity' )
			)
		);

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'footer-right' );
	}

	// Activity Sidebar
	if ( infinity_sidebar_is_empty( 'activity-sidebar' ) ) {

		// set widget(s)
		infinity_sidebars_set_bp_whos_online( 'activity-sidebar' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'activity-sidebar' );
	}

	// Activity Sidebar
	if ( infinity_sidebar_is_empty( 'member-sidebar' ) ) {

		// set widget(s)
		infinity_sidebars_set_bp_whos_online( 'member-sidebar' );
		infinity_sidebars_set_bp_recently_active( 'member-sidebar' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'member-sidebar' );
	}

	// Group Sidebar
	if ( infinity_sidebar_is_empty( 'groups-sidebar' ) ) {

		// set widget(s)
		infinity_sidebars_set_bp_groups(
			'groups-sidebar',
			array(
				'title' => '',
				'max_groups'  => 5,
			)
		);

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'groups-sidebar' );
	}

	// Homepage Right
	if ( infinity_sidebar_is_empty( 'forums-sidebar' ) ) {

		// set widget(s)
		infinity_sidebars_set_bbp_views( 'forums-sidebar' );
		infinity_sidebars_set_bbp_topics( 'forums-sidebar' );
		infinity_sidebars_set_bbp_replies( 'forums-sidebar' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'forums-sidebar' );
	}

	// Wiki Sidebar
	if ( infinity_sidebar_is_empty( 'wiki-sidebar' ) ) {

		// set widget(s)
		infinity_sidebars_set_bpdw_welcome( 'wiki-sidebar' );
		infinity_sidebars_set_bpdw_create_new( 'wiki-sidebar' );
		infinity_sidebars_set_bpdw_tag_cloud( 'wiki-sidebar' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'wiki-sidebar' );
	}

	// Wiki Top
	if ( infinity_sidebar_is_empty( 'wiki-top' ) ) {

		// set widget(s)
		infinity_sidebars_set_bpdw_welcome_intro( 'wiki-top' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'wiki-top' );
	}

	// Wiki Bottom Left
	if ( infinity_sidebar_is_empty( 'wiki-bottom-left' ) ) {

		// set widget(s)
		infinity_sidebars_set_bpdw_recently_active( 'wiki-bottom-left' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'wiki-bottom-left' );
	}

	// Wiki Bottom Right
	if ( infinity_sidebar_is_empty( 'wiki-bottom-right' ) ) {

		// set widget(s)
		infinity_sidebars_set_bpdw_most_active( 'wiki-bottom-right' );

		// call hook
		do_action( 'infinity_sidebars_auto_populate', 'wiki-bottom-right' );
	}

}


//
// Routines
//

/**
 * Register one sidebar
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @package Infinity
 * @subpackage base
 * @see register_sidebar()
 * @param string $id Sidebar ID, 'id' arg passed to register_sidebar()
 * @param string $name Sidebar name, 'name' arg passed to register_sidebar()
 * @param string $desc Sedebar description, 'description' arg passed to register_sidebar()
 */
function infinity_base_register_sidebar( $id, $name, $desc )
{
	register_sidebar( array(
		'id' => $id,
		'name' => $name,
		'description' => $desc,
		'before_widget' => '<article id="%1$s" class="widget %2$s">',
		'after_widget' => '</article>',
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));
}

/**
 * Register base sidebars
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_base_register_sidebars()
{
	// Global
	infinity_base_register_sidebar(
		'sitewide-sidebar',
		'Sitewide Sidebar',
		'Sitewide widget area'
	);
	// page
	infinity_base_register_sidebar(
		'home-sidebar',
		'Home Sidebar',
		'The home widget area'
	);
	// blog
	infinity_base_register_sidebar(
		'blog-sidebar',
		'Blog Sidebar',
		'The blog widget area'
	);
	// page
	infinity_base_register_sidebar(
		'page-sidebar',
		'Page Sidebar',
		'The page widget area'
	);
	// footer left
	infinity_base_register_sidebar(
		'footer-left',
		'Footer Left',
		'The left footer widget'
	);
	// footer middle
	infinity_base_register_sidebar(
		'footer-middle',
		'Footer Middle',
		'The middle footer widget'
	);
	// footer right
	infinity_base_register_sidebar(
		'footer-right',
		'Footer Right',
		'The right footer widget'
	);
	// homepage top right
	infinity_base_register_sidebar(
		'homepage-top-right',
		'Homepage Top Right',
		'The Top Right Widget next to the Slider'
	);
	// homepage center
	infinity_base_register_sidebar(
		'homepage-center-widget',
		'Homepage Center Widget',
		'The Full Width Center Widget on the Homepage'
	);
	// homepage left
	infinity_base_register_sidebar(
		'homepage-left',
		'Homepage Left',
		'The Left Widget on the Homepage'
	);
	// homepage middle
	infinity_base_register_sidebar(
		'homepage-middle',
		'Homepage Middle',
		'The Middle Widget on the Homepage'
	);
	// homepage right
	infinity_base_register_sidebar(
		'homepage-right',
		'Homepage Right',
		'The right Widget on the Homepage'
	);
}

/**
 * Register BuddyPress sidebars
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_base_register_bp_sidebars()
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
 * Show one sidebar
 *
 * @package Infinity
 * @subpackage base
 * @param $index Sidebar index (slug) to display
 * @param $admin_text Text for header above link to widgets manager
 * @return boolean Returns true if sidebar is active and was loaded
 */
function infinity_base_sidebar( $index, $admin_text )
{
	// check if its active
	if ( is_active_sidebar( $index ) ) {
		// yep spit it out
		dynamic_sidebar( $index );
		// sidebar was loaded
		return true;
	// can current user monkey with widgets?
	} elseif ( current_user_can( 'edit_theme_options' ) ) {
		// yep, spit out a nice link ?>
		<aside class="widget">
			<h4><?php print $admin_text ?></h4>
			<a href="<?php echo home_url( '/'  ); ?>wp-admin/widgets.php" title="Add Widgets">Add Widgets</a>
		</aside><?php
	}

	// sidebar not loaded
	return false;
}

/**
 * Show sidebars based on page type (including BP components)
 *
 * @package Infinity
 * @subpackage base
 */
function infinity_base_sidebars()
{
	// is sidebar setup toggled on?
	if ( current_theme_supports( 'infinity:sidebar', 'setup' ) ) {

		// show global sidebar (always try to load this one)
		infinity_base_sidebar( 'sitewide-sidebar', 'Sitewide Sidebar' );

	} else {
		// sidebars toggle off, nothing to do!
		return false;
	}

	// is BP sidebar feature toggled on?
	if (
		current_theme_supports( 'infinity:bp', 'sidebar-setup' ) &&
		function_exists( 'bp_is_user' ) &&
		function_exists( 'bp_is_current_component' ) &&
		is_page()
	) {

		// any profile page, or any members component page?
		if ( bp_is_user() || bp_is_current_component( 'members' ) ) {

			// show member sidebar
			return infinity_base_sidebar( 'member-sidebar', 'BP Member Sidebar' );

		// any groups component page, except member groups?
		} elseif ( !bp_is_user() && bp_is_current_component( 'groups' ) ) {

			// show groups sidebar
			return infinity_base_sidebar( 'groups-sidebar', 'BP Group Sidebar' );

		// any forums component page, except profile pages?
		} elseif ( !bp_is_user() && bp_is_current_component( 'forums' ) ) {

			// show forums sidebar
			return infinity_base_sidebar( 'forums-sidebar', 'BP Forums Sidebar' );

		// any blogs component page, except member blogs?
		} elseif ( !bp_is_user() && bp_is_current_component( 'blogs' )) {

			// show blogs sidebar
			return infinity_base_sidebar( 'blogs-sidebar', 'BP Blogs Sidebar' );

		// any activity component page, except member activity?
		} elseif ( !bp_is_user() && bp_is_current_component( 'activity' ) ) {

			// show activity sidebar
			return infinity_base_sidebar( 'activity-sidebar', 'Activity Sidebar' );
		}
	}

	// if a BP sidebar was output, this function
	// would have been exited by this point!!!

	// front page?
	if ( is_page() && is_front_page() ) {

		// show home sidebar
		return infinity_base_sidebar( 'home-sidebar', 'Home Sidebar' );

	} elseif ( is_forum_page() ) {

		// show forums sidebar
		return infinity_base_sidebar( 'forums-sidebar', 'Forums Sidebar' );

	// any other page?
	} elseif ( is_page() ) {

		// show page sidebar
		return infinity_base_sidebar( 'page-sidebar', 'Page Sidebar' );

	// assume its the "blog" (posts)
	} else {

		// show blog sidebar
		return infinity_base_sidebar( 'blog-sidebar', 'Blog Sidebar' );
	}

	// only showed global sidebar
	return true;

}

//
// Helpers
//

/**
 * Returns true if sidebar with given id has no widgets assigned to it.
 *
 * @param string $sidebar_id
 * @return bool
 */
function infinity_sidebar_is_empty( $sidebar_id )
{
	return ( false === ICE_Widget_Setter::is_sidebar_populated( $sidebar_id ) );
}

/**
 * Set a simple "Welcome" widget that includes links to Join and Login screens by default.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_welcome( $sidebar_id, $args = array() )
{
	// setting defaults
	$defaults = array(
		'title' => __( 'Welcome', 'infinity' ),
		'text'  => null,
		'filter' => false
	);

	// final settings
	$settings = wp_parse_args( $args, $defaults );

	// handle null text
	if ( null === $settings['text'] ) {

		// is buddypress supported?
		if ( true === infinity_plugin_supported( 'buddypress' ) ) {
			// yes, use bp registration page
			$reg_url = bp_get_root_domain() . '/' . bp_get_signup_slug();
		} else {
			// no, use default WordPress registration link
			$reg_url = wp_registration_url();
		}

		// set default text
		$settings['text'] =
			'<p>' .
				sprintf(
					__( '<a %1$s>Join us</a> or <a %2$s>Login</a>', 'infinity' ),
					'class="button" href="' . $reg_url . '"',
					'class="button" href="' . wp_login_url() . '"'
				) .
			'</p>' .
			'<p>' .
				sprintf(
					__( 'To modify the text of this widget, and other widgets you see throughout the site, visit <a %1$s>Dashboard > Appearance > Widgets</a>.', 'infinity' ),
					'href="' . admin_url( 'widgets.php' ) . '"'
				) .
			'</p>';

	}

	// set the widget
	return ICE_Widget_Setter::set_widget( $sidebar_id, 'text', $settings );
}

/**
 * Set a generic "Contact Us" widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_contact( $sidebar_id, $args = array() )
{
	// setting defaults
	$defaults = array(
		'title' => __( 'Contact Us', 'infinity' ),
		'text'  => __( 'Put your contact information in this widget.', 'infinity' ),
		'filter' => false
	);

	// final settings
	$settings = wp_parse_args( $args, $defaults );
	
	// set the widget
	return ICE_Widget_Setter::set_widget( $sidebar_id, 'text', $settings );
}

/**
 * Set a generic "About Us" widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_about( $sidebar_id, $args = array() )
{
	// setting defaults
	$defaults = array(
		'title' => __( 'About', 'infinity' ),
		'text'  => __( 'Some brief information about your site.', 'infinity' ),
		'filter' => false
	);

	// final settings
	$settings = wp_parse_args( $args, $defaults );

	// set the widget
	return ICE_Widget_Setter::set_widget( $sidebar_id, 'text', $settings );
}

/**
 * Set a generic search box widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_search( $sidebar_id, $args = array() )
{
	// setting defaults
	$defaults = array(
		'title' => __( 'Search', 'infinity' ),
		'filter' => false
	);

	// final settings
	$settings = wp_parse_args( $args, $defaults );

	// set the widget
	return ICE_Widget_Setter::set_widget( $sidebar_id, 'search', $settings );
}

/**
 * Set a generic archives widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_archives( $sidebar_id, $args = array() )
{
	// setting defaults
	$defaults = array(
		'title' => __( 'Archives', 'infinity' ),
		'filter' => false
	);

	// final settings
	$settings = wp_parse_args( $args, $defaults );

	// set the widget
	return ICE_Widget_Setter::set_widget( $sidebar_id, 'archives', $settings );
}

/**
 * Set a generic pages widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_pages( $sidebar_id, $args = array() )
{
	// setting defaults
	$defaults = array(
		'title' => __( 'Pages', 'infinity' ),
		'filter' => false
	);

	// final settings
	$settings = wp_parse_args( $args, $defaults );

	// set the widget
	return ICE_Widget_Setter::set_widget( $sidebar_id, 'pages', $settings );
}

/**
 * Set a generic recent posts widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_recent_posts( $sidebar_id, $args = array() )
{
	// setting defaults
	$defaults = array(
		'title' => __( 'Recent Blog Posts', 'infinity' ),
		'filter' => false
	);

	// final settings
	$settings = wp_parse_args( $args, $defaults );

	// set the widget
	return ICE_Widget_Setter::set_widget( $sidebar_id, 'recent-posts', $settings );
}

/**
 * Set a BuddyPress recent blog posts widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bp_recent_posts( $sidebar_id, $args = array() )
{
	// check reqs for sitewide recent posts
	if (
		// buddypress supported?
		true === infinity_plugin_supported( 'buddypress' ) &&
		// blogs component active?
		true === bp_is_active( 'blogs' ) &&
		// multisite install?
		true === is_multisite() &&
		// custom widget registered?
		true === ICE_Widget_Setter::widget_exists( 'infinity_bp_blogs_recent_posts_widget' )
	) {
		// setting defaults
		$defaults = array(
			'title' => __( 'Recent Blog Posts', 'infinity' ),
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'infinity_bp_blogs_recent_posts_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a BuddyPress recently active members widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bp_recently_active( $sidebar_id, $args = array() )
{
	// is buddypress supported?
	if ( true === infinity_plugin_supported( 'buddypress' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Recently Active Members', 'infinity' ),
			'max_members' => 15,
			'filter' => false,
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bp_core_recently_active_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a BuddyPress who's online widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bp_whos_online( $sidebar_id, $args = array() )
{
	// is buddypress supported?
	if ( true === infinity_plugin_supported( 'buddypress' ) ) {
		
		// setting defaults
		$defaults = array(
			'title' => __( "Who's Online", 'infinity' ),
			'max_members' => 20,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bp_core_whos_online_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a generic BuddyPress members list widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bp_members( $sidebar_id, $args = array() )
{
	// is buddypress supported and groups component active?
	if ( true === infinity_plugin_supported( 'buddypress' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Members', 'infinity' ),
			'max_members' => 20,
			'link_title' => 1,
			'member_default' => 'newest',
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bp_core_members_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a generic BuddyPress groups list widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bp_groups( $sidebar_id, $args = array() )
{
	// is buddypress supported and groups component active?
	if (
		true === infinity_plugin_supported( 'buddypress' ) &&
		true === bp_is_active( 'groups' )
	) {
		// setting defaults
		$defaults = array(
			'title' => __( 'Groups', 'infinity' ),
			'max_groups'  => 20,
			'link_title' => 1,
			'group_default' => 'newest',
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bp_groups_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a generic bbPress topic list widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bbp_views( $sidebar_id, $args = array() )
{
	// is bbPress supported?
	if ( true === infinity_plugin_supported( 'bbpress' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Topic View List', 'infinity' ),
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bbp_views_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a generic bbPress recent topics list widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bbp_topics( $sidebar_id, $args = array() )
{
	// is bbPress supported?
	if ( true === infinity_plugin_supported( 'bbpress' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Recent Topics', 'infinity' ),
			'max_shown' => 6,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bbp_topics_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a generic bbPress recent replies widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bbp_replies( $sidebar_id, $args = array() )
{
	// is bbPress supported?
	if ( true === infinity_plugin_supported( 'bbpress' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Recent Replies', 'infinity' ),
			'max_shown' => 6,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bbp_replies_widget', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a helpful "Welcome to the Wiki" widget.
 *
 * The default text is intended for use in a sidebar which is displayed on all wiki pages.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bpdw_welcome( $sidebar_id, $args = array() )
{
	// is bp docs wiki supported?
	if ( true === infinity_plugin_supported( 'buddypress-docs-wiki' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Welcome To The Wiki', 'infinity' ),
			'text' => null,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// handle null text
		if ( null === $settings['text'] ) {

			// set default text
			$settings['text'] =
				'<p>' .
					sprintf(
						__( 'This sidebar appears on all Wiki pages. Use it to display content that you want your users to see whenever viewing the Wiki, such as a brief description of how wikis work, or a link to <a %1$s>create a new wiki page</a>.', 'infinity' ),
						'href="' . infinity_bp_docs_wiki_create_url() . '"'
					) .
				'</p>' .
				'<p>' .
					sprintf(
						__( 'To edit this widget, or to add more widgets to the sidebar, visit <a %1$s>Dashboard > Appearance > Widgets</a> and look for the Wiki Sidebar.', 'infinity' ),
						'href="' . admin_url( 'widgets.php' ) . '"'
					) .
				'</p>';

		}

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'text', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a helpful "Welcome to the Wiki Home" widget.
 *
 * The default text is intended to be useful in a sidebar that only displays on the wiki root screen.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bpdw_welcome_intro( $sidebar_id, $args = array() )
{
	// is bp docs wiki supported?
	if ( true === infinity_plugin_supported( 'buddypress-docs-wiki' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Welcome To The Wiki Home', 'infinity' ),
			'text' => null,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// handle null text
		if ( null === $settings['text'] ) {

			// set default text
			$settings['text'] =
				'<p>' .
					__( 'This is a text widget that you can use to introduce your users to the wiki, and perhaps to feature some outstanding wiki content.', 'infinity' ) .
				'</p>' .
				'<p>' .
					sprintf(
						__( 'Edit this widget, or add others to the Wiki Top sidebar, at <a %1$s>Dashboard > Appearance > Widgets</a>.', 'infinity' ),
						'href="' . admin_url( 'widgets.php' ) . '"'
					) .
				'</p>';
		}

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'text', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a generic widget that contains a button which links to the new wiki page screen.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bpdw_create_new( $sidebar_id, $args = array() )
{
	// is bp docs wiki supported?
	if ( true === infinity_plugin_supported( 'buddypress-docs-wiki' ) ) {

		// setting defaults
		$defaults = array(
			'title' => '',
			'text' => null,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// handle null text
		if ( null === $settings['text'] ) {

			// set default text
			$settings['text'] =
				'<a href="' . infinity_bp_docs_wiki_create_url() . '" class="button">' .
					__( 'Create New Wiki Page', 'infinity' ) .
				'</a>';

		}

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'text', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a simple wiki tag cloud widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bpdw_tag_cloud( $sidebar_id, $args = array() )
{
	// is bp docs wiki supported?
	if ( true === infinity_plugin_supported( 'buddypress-docs-wiki' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Wiki Tags', 'infinity' ),
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bpdw_tag_cloud', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a most active wiki pages widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bpdw_most_active( $sidebar_id, $args = array() )
{
	// is buddypress supported?
	if ( true === infinity_plugin_supported( 'buddypress-docs-wiki' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Most Active', 'infinity' ),
			'max_pages' => 5,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bpdw_most_active', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a recently active wiki pages widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_bpdw_recently_active( $sidebar_id, $args = array() )
{
	// is buddypress supported?
	if ( true === infinity_plugin_supported( 'buddypress-docs-wiki' ) ) {

		// setting defaults
		$defaults = array(
			'title' => __( 'Recently Active', 'infinity' ),
			'max_pages' => 5,
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'bpdw_recently_active', $settings );
	}

	// widget NOT set
	return false;
}

/**
 * Set a generic CAC Featured member widget.
 *
 * @param string $sidebar_id The id of the target sidebar.
 * @param array $args Optional array of widget settings for overriding defaults.
 * @return WP_Error|bool
 */
function infinity_sidebars_set_cacfc_member( $sidebar_id, $args = array() )
{
	global $wpdb;

	// is CAC featured content widget plugin supported?
	if ( true === infinity_plugin_supported( 'cac-featured-content' ) ) {

		// default settings
		$defaults = array(
			'title' => __( 'Featured Member', 'infinity' ),
			'title_element' => 'h4',
			'featured_member' => '',
			'display_images' => '1',
			'crop_length' => '250',
			'image_width' => '50',
			'image_height' => '50',
			'image_url' => '',
			'read_more' => '',
			'filter' => false
		);

		// final settings
		$settings = wp_parse_args( $args, $defaults );

		// override whatever content type they *may* have set with "member"
		$settings['featured_content_type'] = 'member';

		// is featured_member is empty?
		if ( true === empty( $settings['featured_member'] ) ) {
			// yes, pull up a random member to populate
			$settings['featured_member'] = $wpdb->get_var( "SELECT user_login FROM {$wpdb->users} ORDER BY RAND()" );
		}

		// set the widget
		return ICE_Widget_Setter::set_widget( $sidebar_id, 'cac_featured_content_widget', $settings );
	}

	// widget NOT set
	return false;
}
