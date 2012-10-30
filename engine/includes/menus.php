<?php
/**
 * Infinity Theme: menus
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2012 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.1
 */

/**
 * Register and create a custom BuddyPress menu
 */
function infinity_register_bp_menu( $menu_name )
{
	global $blog_id;

	// check BP reqs and if our custom default menu already exists
	if (
		function_exists( 'bp_core_get_directory_pages' ) &&
		BP_ROOT_BLOG == $blog_id &&
		!is_nav_menu( $menu_name )
	) {
		// doesn't exist, create it
		$menu_id = wp_create_nav_menu( $menu_name );

		// get bp pages
		$pages = bp_core_get_directory_pages();

		// allowed pages
		$pages_ok = array(
			'activity' => true,
			'blogs' => true,
			'forums' => true,
			'groups' => true,
			'links' => true,
			'members' => true
		);

		// loop all pages
		foreach( $pages as $config ) {
			// make sure we support this page
			if ( array_key_exists( $config->name, $pages_ok ) ) {
				// yep, add page as a nav item
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-type' => 'post_type',
					'menu-item-status' => 'publish',
					'menu-item-object' => 'page',
					'menu-item-object-id' => $config->id,
					'menu-item-title' => $config->title,
					'menu-item-attr-title' => $config->title,
					'menu-item-classes' => 'icon-' . $config->name
				));
			}
		}

		// get location settings
		$locations = get_theme_mod( 'nav_menu_locations' );

		// is main menu location set yet?
		if ( empty( $locations['main-menu'] ) ) {
			// nope, set it
			$locations['main-menu'] = $menu_id;
			// update theme mode
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}

/**
 * Add a filter for every displayed user navigation item
 */
function infinity_bp_nav_inject_options_setup()
{
	global $bp;

	// loop all nav components
	foreach ( (array)$bp->bp_nav as $user_nav_item ) {
		// add navigation filter
		add_filter(
			'bp_get_displayed_user_nav_' . $user_nav_item['css_id'],
			'infinity_bp_nav_inject_options_filter',
			999,
			2
		);
	}
}

/**
 * Inject options nav onto end of active displayed user nav component
 *
 * @param string $html
 * @param array $user_nav_item
 * @return string
 */
function infinity_bp_nav_inject_options_filter( $html, $user_nav_item )
{
	// component slugs to show subnavs for
	$show = array(
		'activity' => true,
		'blogs' => true,
		'forums' => true
	);

	// add these slugs for logged in users viewing their own profile
	if ( bp_is_my_profile() ) {
		$show['friends'] = true;
		$show['groups'] = true;
		$show['messages'] = true;
		$show['profile'] = true;
		$show['settings'] = true;
	}

	// is slug the current component and should we show it?
	if (
		bp_is_current_component( $user_nav_item['slug'] ) &&
		array_key_exists( $user_nav_item['slug'], $show )
	) {

		// yes, need to capture options nav output
		ob_start();

		// run options nav template tag
		bp_get_options_nav();

		// grab buffer and wipe it
		$nav = ob_get_clean();

		// inject nav onto end of list item wrapped in special <ul>
		return preg_replace(
			'/(<\/li>.*)$/',
			'<ul class="profile-subnav">' . $nav . '</ul>' . '$1',
			$html,
			1
		);
	}

	// no changes
	return $html;
}

?>