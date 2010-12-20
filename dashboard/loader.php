<?php
/**
 * Infinity theme dashboard loader
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage loader
 * @since 1.0
 */

//
// Constants
//
define( 'INFINITY_ADMIN_PAGE', 'infinity-cpanel' );
define( 'INFINITY_ADMIN_TPLS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'templates' );
define( 'INFINITY_ADMIN_DOCS_DIR', INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'docs' );
define( 'INFINITY_ROUTE_PARAM', 'route' );
define( 'INFINITY_ROUTE_DELIM', '/' );

//
// Files
//
require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'menu.php' );
require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'cpanel.php' );

//
// Actions
//
add_action( 'admin_init', 'infinity_ajax_setup' );
add_action( 'admin_menu', 'infinity_dashboard_menu_setup' );
add_action( 'admin_menu', 'infinity_dashboard_cpanel_setup' );

//
// Functions
//

/**
 * Setup AJAX handling
 */
function infinity_ajax_setup()
{
	if ( defined( 'DOING_AJAX' ) ) {
		Infinity_Options::init_ajax();
	}
}

/**
 * Handle setup of the control panel environment
 */
function infinity_dashboard_cpanel_setup()
{
	// setup dashboard if its active
	$action = infinity_dashboard_cpanel_action();

	if ( $action ) {

		// pie easy options init
		Infinity_Options::init();

		// add content hook
		add_action(
			'infinity_dashboard_cpanel_content',
			sprintf( 'infinity_dashboard_cpanel_%s_content', $action )
		);

		// enqueue styles
		wp_enqueue_style( INFINITY_ADMIN_PAGE, INFINITY_ADMIN_URL . '/assets/css/cpanel.css', false, INFINITY_VERSION, 'screen' );

		// enqueue script
		wp_enqueue_script( INFINITY_ADMIN_PAGE, INFINITY_ADMIN_URL . '/assets/js/dashboard.js', false, INFINITY_VERSION );

	}
}

/**
 * Load a dashboard template relative to the template dir root
 *
 * @param string $rel_path
 */
function infinity_dashboard_load_template( $rel_path )
{
	include( INFINITY_ADMIN_TPLS_DIR . DIRECTORY_SEPARATOR . $rel_path );
}

/**
 * Build a page/route URL from screen, action, and params
 *
 * @param string $screen
 * @param string $action
 * @param string $params,...
 * @return array
 */
function infinity_dashboard_route( $screen, $action = null, $params = null )
{
	// usinging variable args
	$args = func_get_args();
	
	// build the base URL
	$url = sprintf( '%sadmin.php?page=%s-%s', admin_url(), INFINITY_NAME, array_shift($args) );
	
	// remaining args are our route
	if ( count( $args ) ) {
		$url .= sprintf( '&%s=%s', INFINITY_ROUTE_PARAM, implode( INFINITY_ROUTE_DELIM, $args ) );
	}

	return $url;
}

/**
 * Parse the page/route into screen, action, and params
 * 
 * @return array
 */
function infinity_dashboard_parse_route()
{
	// the route defaults
	$route = array(
		'screen' => null,
		'action' => null,
		'params' => null,
	);

	// page contains screen
	if ( isset( $_GET['page'] ) ) {
		// split at hyphen
		$page_toks = explode( '-', $_GET['page'] );
		// must be exactly two tokens
		if ( count( $page_toks ) == 2 && $page_toks[0] == INFINITY_NAME ) {
			// second token is the screen
			$route['screen'] = $page_toks[1];
			// check if a route is set
			if ( isset( $_GET[INFINITY_ROUTE_PARAM] ) ) {
				// get route tokens
				$route_toks = explode( INFINITY_ROUTE_DELIM, $_GET[INFINITY_ROUTE_PARAM] );
				// get at least one token?
				if ( count( $route_toks ) ) {
					// first token is the action
					$route['action'] = array_shift($route_toks);
					// remaining tokens are params
					$route['params'] = $route_toks;
				}
			}
		}
	}

	return $route;
}

/**
 * Return path to a dashboard image
 */
function infinity_dashboard_image( $name )
{
	return INFINITY_ADMIN_URL . '/assets/images/' . $name;
}

?>
