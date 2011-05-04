<?php
/**
 * Infinity Theme: dashboard control panel functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage control-panel
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
	$route = infinity_dashboard_route_parse();

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

		// init options
		infinity_scheme_init();

		// init options
		infinity_options_init();

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

	if ( $action ) {
		Pie_Easy_Ajax::responseBegin();
		infinity_dashboard_load_template( sprintf( 'cpanel/%s.php', $action ) );
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


/**
 * Display options form
 *
 * @param array|stdClass Variables to inject into template
 */
function infinity_dashboard_cpanel_options_content( $args = null )
{
	$defaults->menu_args = null;

	infinity_dashboard_load_template( 'cpanel/options.php', $args, $defaults );
}

?>
