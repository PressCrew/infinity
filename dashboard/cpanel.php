<?php
/**
 * Infinity Theme: dashboard control panel functions
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
// Hooks
//
add_action( 'admin_init', 'infinity_dashboard_cpanel_setup' );
////

/**
 * Return the current dashboard action
 *
 * @package Infinity
 * @subpackage dashboard
 * @return string|null
 */
function infinity_dashboard_cpanel_action()
{
	$route = infinity_screens_route_parse();

	if ( ($route) && $route['screen'] == 'cpanel' ) {
		if ( $route['action'] ) {
			return $route['action'];
		} else {
			return array_shift( infinity_dashboard_cpanel_actions() );
		}
	}

	return null;
}

/**
 * Return actions configuration array
 *
 * @package Infinity
 * @subpackage dashboard
 * @return array
 */
function infinity_dashboard_cpanel_actions()
{
	return array(
		// main
		'start', 'options'
	);
}

/**
 * Initialize a control panel instance
 *
 * @package Infinity
 * @subpackage dashboard
 * @return ICE_Ui_Cpanel
 */
function infinity_dashboard_cpanel_factory()
{
	ICE_Loader::load( 'ui/cpanel' );

	// new control panel instance using screens policy
	$cpanel = new ICE_Ui_Cpanel( ICE_Policy::screens() );
	
	return $cpanel;
}

/**
 * Begin control panel rendering
 *
 * @package Infinity
 * @subpackage dashboard
 * @param string $id_prefix The CSS id prefix for dynamic elements
 */
function infinity_dashboard_cpanel_render_begin( $id_prefix )
{
	global $infinity_c8c12e68cf;

	$infinity_c8c12e68cf = infinity_dashboard_cpanel_factory();
	$infinity_c8c12e68cf->render_begin( 'infinity-cpanel-' );
}

/**
 * Render control panel tabs list items
 *
 * @package Infinity
 * @subpackage dashboard
 */
function infinity_dashboard_cpanel_render_tab_list()
{
	global $infinity_c8c12e68cf;

	$infinity_c8c12e68cf->render_tab_list( infinity_dashboard_cpanel_actions() );
}

/**
 * Render control panel tabs content items
 *
 * @package Infinity
 * @subpackage dashboard
 */
function infinity_dashboard_cpanel_render_tab_panels()
{
	global $infinity_c8c12e68cf;

	$infinity_c8c12e68cf->render_tab_panels( infinity_dashboard_cpanel_actions() );
}

/**
 * End rendering
 *
 * @package Infinity
 * @subpackage dashboard
 */
function infinity_dashboard_cpanel_render_end()
{
	global $infinity_c8c12e68cf;

	$infinity_c8c12e68cf->render_end();

	unset( $infinity_c8c12e68cf );
}

//
// Actions
//

/**
 * Handle setup of the control panel environment
 *
 * @package Infinity
 * @subpackage dashboard
 */
function infinity_dashboard_cpanel_setup()
{
	// setup dashboard if its active
	$action = infinity_dashboard_cpanel_action();

	if ( $action ) {

		// always need jQuery UI (for now)
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-progressbar' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		
		// hook for config actions
		do_action( 'infinity_dashboard_cpanel_setup' );
	}
}

//
// Screens
//

/**
 * Route requests and display the control panel
 *
 * @package Infinity
 * @subpackage dashboard
 */
function infinity_dashboard_cpanel_screen()
{
	infinity_dashboard_load_template( 'cpanel.php' );
}

?>
