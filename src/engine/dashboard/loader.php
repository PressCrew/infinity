<?php
/**
 * Infinity Theme: dashboard loader
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage dashboard
 * @since 1.0
 */

//
// Functions
//

/**
 * Fire activation hook if theme was just activated
 */
function infinity_dashboard_activated()
{
	// exec activation hook
	do_action( 'infinity_dashboard_activated' );
}
add_action( 'ice_theme_activated', 'infinity_dashboard_activated' );

/**
 * Redirect to admin page on activation
 */
function infinity_dashboard_activated_redirect()
{
	// redirect to admin page
	wp_redirect( admin_url( 'themes.php?page=' . INFINITY_ADMIN_PAGE ) );
	// no more exec
	exit;
}
add_action( 'infinity_dashboard_activated', 'infinity_dashboard_activated_redirect', 99 );

/**
 * Adds the Infinity submenu item to the WordPress menu
 *
 * @package Infinity
 * @subpackage dashboard
 */
function infinity_dashboard_menu_setup()
{
	// get name of current theme
	$theme_name = wp_get_theme()->Name;

	// format page title
	$page_title = sprintf( __( '%s Options', 'infinity-engine' ), $theme_name );

	// add appearance submenu item
	add_theme_page(
		apply_filters( 'infinity_dashboard_menu_setup_page_title', $page_title, $theme_name ),
		apply_filters( 'infinity_dashboard_menu_setup_menu_title', $page_title, $theme_name ),
		'edit_theme_options',
		INFINITY_ADMIN_PAGE,
		'infinity_dashboard_cpanel_screen'
	);
}
add_action( 'admin_menu', 'infinity_dashboard_menu_setup' );

/**
 * Locate a dashboard template relative to the template dir root
 *
 * @package Infinity
 * @subpackage dashboard
 * @param string $rel_path Relative path to template from dashboard template root
 * @return string
 */
function infinity_dashboard_locate_template( $rel_path )
{
	// format template path
	$template = 'templates/dashboard/' . $rel_path;

	// locate the template
	return infinity_locate_template( $template );
}

/**
 * Load a dashboard template relative to the template dir root
 *
 * @package Infinity
 * @subpackage dashboard
 * @param string $rel_path Relative path to template from dashboard template root
 * @param array|stdClass $args Variables to inject into template
 * @param array|stdClass $defaults Default values of variables being injected into template
 */
function infinity_dashboard_load_template( $rel_path, $args = null, $defaults = null )
{
	// populate local scope
	extract( wp_parse_args( $args, (array) $defaults ) );

	// locate and include the template
	include infinity_dashboard_locate_template( $rel_path );
}

/**
 * Return path to a dashboard image
 *
 * @package Infinity
 * @subpackage dashboard
 * @param string $name image file name
 * @return string
 */
function infinity_dashboard_image( $name )
{
	$path = infinity_locate_file( INFINITY_THEME_PATH . '/assets/images/dashboard/' . $name );

	if ( $path ) {
		return ICE_Files::file_to_site_url( $path );
	} else {
		return '';
	}
}

/**
 * Return URL to a screen component route
 *
 * @package Infinity
 * @subpackage dashboard
 * @param string $params,...
 * @return string
 */
function infinity_dashboard_screen_url()
{
	$args = func_get_args();
	
	$url = INFINITY_AJAX_URL . call_user_func_array( 'infinity_screens_route', $args );
	
	return apply_filters( 'infinity_dashboard_screen_url', $url, $args );
}
