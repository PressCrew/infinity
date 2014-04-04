<?php
/**
 * Infinity Theme: screens classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-api
 * @subpackage screens
 * @since 1.0
 */

ICE_Loader::load(
	'components/screens/component',
	'components/screens/factory',
	'components/screens/policy',
	'components/screens/registry',
	'components/screens/renderer'
);

/**
 * Name of the route param (GET|POST)
 */
define( 'INFINITY_ROUTE_PARAM', 'route' );

/**
 * String on which to split route
 */
define( 'INFINITY_ROUTE_DELIM', '/' );

//
// Helpers
//

/**
 * Build a page/route URL
 *
 * @package Infinity-api
 * @subpackage screens
 * @param string|array $params,...
 * @return array
 */
function infinity_screens_route()
{
	// build the base URL
	$url = sprintf( '?page=%s', INFINITY_ADMIN_PAGE );

	// use variable args
	$params = func_get_args();

	// check if first is an array
	if ( is_array( current($params) ) ) {
		$params = $params[0];
	}

	// add route if necessary
	if ( count( $params ) ) {
		$url .= sprintf( '&%s=%s', INFINITY_ROUTE_PARAM, implode( INFINITY_ROUTE_DELIM, $params ) );
	}

	return $url;
}

/**
 * Parse the page/route into screen, action, and params
 *
 * @package Infinity-api
 * @subpackage screens
 * @return array
 */
function infinity_screens_route_parse()
{
	// the route string
	$route_string = '';

	// look for route string in request
	if ( ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == INFINITY_ADMIN_PAGE ) ) {
		// look for route in request, post has priority
		if ( isset( $_POST[INFINITY_ROUTE_PARAM] ) )  {
			$route_string = $_POST[INFINITY_ROUTE_PARAM];
		} elseif ( isset( $_GET[INFINITY_ROUTE_PARAM] ) )  {
			$route_string = $_GET[INFINITY_ROUTE_PARAM];
		}
	} else {
		return false;
	}

	// have a route string?
	if ( strlen($route_string) ) {
		// get route tokens
		$route_toks = explode( INFINITY_ROUTE_DELIM, $route_string );
		// get at least one token?
		if ( count( $route_toks ) ) {
			// first token is the screen
			$route['screen'] = array_shift($route_toks);
			// second token is the action
			$route['action'] = array_shift($route_toks);
			// remaining tokens are params
			$route['params'] = $route_toks;
			// done
			return $route;
		}
	} else {

		// use route defaults
		return array(
			'screen' => 'cpanel',
			'action' => null,
			'params' => null,
		);

	}
}

/**
 * Retrieve a specific route param by offset
 *
 * @package Infinity-api
 * @subpackage screens
 * @param integer $offset
 * @return mixed
 */
function infinity_screens_route_param( $offset )
{
	$route = infinity_screens_route_parse();

	if ( isset( $route['params'][--$offset] ) ) {
		return $route['params'][$offset];
	}

	return null;
}

//
// Helpers
//

/**
 * Fetch a screen object from the registry
 *
 * @package Infinity-api
 * @subpackage screens
 * @param string $screen_name
 * @return ICE_Screen
 */
function infinity_screen_fetch( $screen_name )
{
	return ICE_Policy::screens()->registry()->get( $screen_name );
}
