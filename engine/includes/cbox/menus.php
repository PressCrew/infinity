<?php

/**
 * Sets up a default sub menu in the CBOX theme.
 *
 * This function is fired on 'get_header' on the frontend to give CBOX
 * components a chance to configure from the admin area (like BP Docs).
 */
function cbox_theme_add_default_sub_menu()
{
	global $blog_id;

	// make sure we are on the root bp blog
	if ( (int) BP_ROOT_BLOG !== (int) $blog_id ) {
		// bail
		return false;
	}
	
	// load menu utils
	ICE_Loader::load( 'utils/menus' );

	// new menu manager using our special name
	$menu_mgr = new ICE_Menu_Manager( 'cbox-sub-menu' );

	// try to register it
	if ( true === $menu_mgr->register( 'Default Social Menu' ) ) {

		// add home page
		$menu_mgr->add_item(
			array(
				'title'		=> _x( 'Home', 'the link in the header navigation bar', 'cbox-theme' ),
				'position'	=> 0,
				'url'		=> home_url( '/' )
			)
		);
		// add members page
		$menu_mgr->add_bp_page(
			'members',
			array(
				'title'		=> _x( 'People', 'the link in the header navigation bar', 'cbox-theme' ),
				'position'	=> 10
			)
		);
		// add groups page
		$menu_mgr->add_bp_page(
			'groups',
			array(
				'title'		=> _x( 'Groups', 'the link in the header navigation bar', 'cbox-theme' ),
				'position'	=> 20
			)
		);
		// add blogs page
		$menu_mgr->add_bp_page(
			'blogs',
			array(
				'title'		=> _x( 'Blogs', 'the link in the header navigation bar', 'cbox-theme' ),
				'position'	=> 30
			)
		);
		// add activity page
		$menu_mgr->add_bp_page(
			'activity',
			array(
				'title'		=> _x( 'Activity', 'the link in the header navigation bar', 'cbox-theme' ),
				'position'	=> 50
			)
		);
		// is BuddyPress Docs Wiki plugin loaded?
		if ( defined( 'BP_DOCS_WIKI_SLUG' ) ) {
			// yep, add wiki page
			$menu_mgr->add_item(
				array(
					'title'		=> _x( 'Wiki', 'the link in the header navigation bar', 'cbox-theme' ),
					'position'	=> 40,
					'url'		=> home_url( 'wiki' )
				)
			);
		}

		// set location
		$menu_mgr->add_location( 'sub-menu' );
	}
}
