<?php
/**
 * Infinity Theme: screens classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-components
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/screens' );

/**
 * Name of the route param (GET|POST)
 */
define( 'INFINITY_ROUTE_PARAM', 'route' );

/**
 * String on which to split route
 */
define( 'INFINITY_ROUTE_DELIM', '/' );

/**
 * Infinity Theme: screens policy
 *
 * @package Infinity-components
 * @subpackage screens
 */
class Infinity_Screens_Policy extends Pie_Easy_Screens_Policy
{
	/**
	 * @return Pie_Easy_Screens_Policy
	 */
	static public function instance()
	{
		self::$calling_class = __CLASS__;
		return parent::instance();
	}
	
	/**
	 */
	final public function get_api_slug()
	{
		return 'infinity_theme';
	}

	/**
	 */
	final public function enable_styling()
	{
		return ( is_admin() );
	}

	/**
	 */
	final public function enable_scripting()
	{
		return ( is_admin() );
	}

	/**
	 * @return Infinity_Screens_Registry
	 */
	final public function new_registry()
	{
		return new Infinity_Screens_Registry();
	}

	/**
	 * @return Infinity_Exts_Screen_Factory
	 */
	final public function new_factory()
	{
		return new Infinity_Exts_Screen_Factory();
	}

	/**
	 * @return Infinity_Screens_Renderer
	 */
	final public function new_renderer()
	{
		return new Infinity_Screens_Renderer();
	}

}

/**
 * Infinity Theme: screens registry
 *
 * @package Infinity-components
 * @subpackage screens
 */
class Infinity_Screens_Registry extends Pie_Easy_Screens_Registry
{
	// nothing custom yet
}

/**
 * Infinity Theme: section factory
 *
 * @package Infinity-extensions
 * @subpackage screens
 */
class Infinity_Exts_Screen_Factory extends Pie_Easy_Screens_Factory
{
	/**
	 */
	public function create( $theme, $name, $conf )
	{
		// DO NOT TRY THIS YOURSELF
		// @todo this is a temporary solution until the configuration strategy is improved

		// call parent to create component
		$component = parent::create( $theme, $name, $conf );

		// check if URL is set
		if ( !$component->url ) {
			// set url
			$def_map = new Pie_Easy_Map();
			$def_map->add( 'url', infinity_screens_route( 'cpanel', $component->name ) );
			// configure it
			$component->configure( $def_map, $theme );
		}

		return $component;
	}
}

/**
 * Infinity Theme: screens renderer
 *
 * @package Infinity-components
 * @subpackage screens
 */
class Infinity_Screens_Renderer extends Pie_Easy_Screens_Renderer
{
	// nothing custom yet
}

//
// Helpers
//

/**
 * Initialize screens environment
 *
 * @package Infinity-components
 * @subpackage screens
 */
function infinity_screens_init()
{
	// component policy
	$screens_policy = Infinity_Screens_Policy::instance();

	// enable component
	Pie_Easy_Scheme::instance()->enable_component( $screens_policy );

	do_action( 'infinity_screens_init' );
}

/**
 * Initialize screens screen requirements
 *
 * @package Infinity-components
 * @subpackage screens
 */
function infinity_screens_init_screen()
{
	// init ajax OR screen reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		Infinity_Screens_Policy::instance()->registry()->init_ajax();
		do_action( 'infinity_screens_init_ajax' );
	} else {
		Infinity_Screens_Policy::instance()->registry()->init_screen();
		do_action( 'infinity_screens_init_screen' );
	}
}

/**
 * Build a page/route URL
 *
 * @package Infinity-components
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
 * @package Infinity-components
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
 * @package Infinity-components
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

?>
