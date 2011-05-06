<?php
/**
 * Infinity Theme: dashboard loader
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage loader
 * @since 1.0
 */

//
// Constants
//
define( 'INFINITY_ROUTE_PARAM', 'route' );
define( 'INFINITY_ROUTE_DELIM', '/' );

//
// Files
//

// admin menu
if ( is_admin() ) {
	require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'menu.php' );
}

// control panel
require_once( INFINITY_ADMIN_DIR . DIRECTORY_SEPARATOR . 'cpanel.php' );


//
// Functions
//

/**
 * Load a dashboard template relative to the template dir root
 *
 * @param string $rel_path Relative path to template from dashboard template root
 * @param array|stdClass $args Variables to inject into template
 * @param array|stdClass $defaults Default values of variables being injected into template
 */
function infinity_dashboard_load_template( $rel_path, $args = null, $defaults = null )
{
	// populate local scope
	extract( wp_parse_args( $args, (array) $defaults ) );

	// include the template
	include( INFINITY_ADMIN_TPLS_DIR . DIRECTORY_SEPARATOR . $rel_path );
}

/**
 * Build a page/route URL
 *
 * @param string|array $params,...
 * @return array
 */
function infinity_dashboard_route()
{
	// build the base URL
	$url = sprintf( '%sadmin.php?page=%s', admin_url(), INFINITY_ADMIN_PAGE );

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
 * @return array
 */
function infinity_dashboard_route_parse()
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
 * @param integer $offset
 * @return mixed
 */
function infinity_dashboard_route_param( $offset )
{
	$route = infinity_dashboard_route_parse();

	if ( isset( $route['params'][--$offset] ) ) {
		return $route['params'][$offset];
	}

	return null;
}

/**
 * Return path to a dashboard image
 */
function infinity_dashboard_image( $name )
{
	return INFINITY_ADMIN_URL . '/assets/images/' . $name;
}

/**
 * Publish a document page
 *
 * @param string $page Name of page to publish
 */
function infinity_dashboard_doc_publish( $page = null )
{
	Pie_Easy_Loader::load( 'utils/docs' );
	$doc = new Pie_Easy_Docs( Pie_Easy_Scheme::instance()->theme_documentation_dirs(), $page );
	$doc->set_pre_filter( 'infinity_dashboard_doc_filter' );
	$doc->publish();
}

/**
 * Publish a developer (core) document page
 *
 * @param string $page Name of page to publish
 */
function infinity_dashboard_devdoc_publish( $page = null )
{
	Pie_Easy_Loader::load( 'utils/docs' );
	$doc = new Pie_Easy_Docs( INFINITY_ADMIN_DOCS_DIR, $page );
	$doc->set_pre_filter( 'infinity_dashboard_doc_filter' );
	$doc->publish();
}

/**
 * Pre filter doc contents before parsing
 *
 * @param string $contents
 * @return string
 */
function infinity_dashboard_doc_filter( $contents )
{
	// replace internal URLs with valid URLs (infinity://admin:foo/cpanel/docs/foo_page)
	return preg_replace_callback( '/infinity:\/\/([a-z]+)(:([a-z]+))?((\/[\w\.]+)*)/', 'infinity_dashboard_doc_filter_cb', $contents );
}

/**
 * Pre filter callback
 *
 * @param array $match
 * @return string
 */
function infinity_dashboard_doc_filter_cb( $match )
{
	// where are we?
	$location = $match[1];

	// call type
	$call_type = $match[3];

	// the route
	$route = trim( $match[4], INFINITY_ROUTE_DELIM );

	switch ( $location ) {
		case 'admin':
			switch( $call_type ) {
				case '':
				case 'action':
					return infinity_dashboard_route( $route );
				case 'image':
					return infinity_dashboard_image( $route );
				case 'doc':
					return infinity_dashboard_route( 'cpanel', 'ddocs', $route );
			}
		case 'theme':
			switch( $call_type ) {
				case '':
				case 'image':
					return infinity_image_url( $route );
				case 'doc':
					return infinity_dashboard_route( 'cpanel', 'docs', $route );
			}
	}
}

?>
