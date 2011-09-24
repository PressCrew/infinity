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
 * @return array
 */
function infinity_dashboard_cpanel_actions()
{
	return array(
		// main
		'start', 'options', 'shortcodes', 'widgets', 'about',
		// devs
		'docs', 'api',
		// community
		'news', 'thanks'
	);
}

/**
 * Initialize and render the control panel markup
 *
 * @param string $header_template Path to header template relative to theme directory
 */
function infinity_dashboard_cpanel_ui( $header_template = null )
{
	Pie_Easy_Loader::load( 'ui/cpanel' );

	// handle empty header template
	if ( empty( $header_template ) ) {
		$header_template = 'dashboard/templates/cpanel_header.php';
	}

	// new control panel instance using screens policy
	$cpanel = new Pie_Easy_Ui_Cpanel( Infinity_Screens_Policy::instance() );

	// start rendering
	$cpanel->render_begin(
		__( 'infinity', infinity_text_domain ),
		'infinity-cpanel'
	);

	// render the required elements
	$cpanel->render_header( $header_template );
	$cpanel->render_toolbar();
	$cpanel->render_tabs();

	// all done
	$cpanel->render_end();
}

//
// Actions
//

/**
 * Handle setup of the control panel environment
 */
function infinity_dashboard_cpanel_setup()
{
	// setup dashboard if its active
	$action = infinity_dashboard_cpanel_action();

	if ( $action ) {

		// tab action
		add_action( 'wp_ajax_infinity_tabs_content', 'infinity_dashboard_cpanel_tabs_content' );

		// hook for config actions
		do_action( 'infinity_dashboard_cpanel_setup' );
	}
}

/**
 * Output cpanel tab content
 */
function infinity_dashboard_cpanel_tabs_content()
{
	$action = infinity_dashboard_cpanel_action();
	$screen = Infinity_Screens_Policy::instance()->registry()->get( $action );

	if ( $screen instanceof Pie_Easy_Screens_Screen ) {
		Pie_Easy_Ajax::responseBegin();
		$screen->render();
		Pie_Easy_Ajax::responseEnd( true );
	} else {
		Pie_Easy_Ajax::responseStd( false, sprintf( __('There was an error while trying to load the %s tab content.', infinity_text_domain), $action ) );
	}
}

//
// Screens
//

/**
 * Route requests and display the control panel
 */
function infinity_dashboard_cpanel_screen()
{
	infinity_dashboard_load_template( 'cpanel.php' );
}

?>
