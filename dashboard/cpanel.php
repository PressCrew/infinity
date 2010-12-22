<?php
/**
 * Infinity theme dashboard control panel functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage control-panel
 * @since 1.0
 */

/**
 * Return the current dashboard action
 *
 * @return string|null
 */
function infinity_dashboard_cpanel_action()
{
	$route = infinity_dashboard_route_parse();
	
	if ( $route['screen'] == 'cpanel' ) {
		if ( $route['action'] ) {
			return $route['action'];
		} else {
			return key( infinity_dashboard_cpanel_actions() );
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
	return
		array(
			'start' => __( 'Start', INFINITY_TEXT_DOMAIN ),
			'widgets' => __( 'Widgets', INFINITY_TEXT_DOMAIN ),
			'shortcodes' => __( 'Shortcodes', INFINITY_TEXT_DOMAIN ),
			'options' => __( 'Options', INFINITY_TEXT_DOMAIN ),
			'docs' => __( 'Docs', INFINITY_TEXT_DOMAIN ),
			'about' => __( 'About', INFINITY_TEXT_DOMAIN ),
			'thanks' => __( 'Thanks', INFINITY_TEXT_DOMAIN )
		);
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

//
// Content Actions
//

/**
 * Display start page
 */
function infinity_dashboard_cpanel_start_content()
{
	infinity_dashboard_load_template( 'cpanel/start.php' );
}

/**
 * Display widgets page
 */
function infinity_dashboard_cpanel_widgets_content()
{
	infinity_dashboard_load_template( 'cpanel/widgets.php' );
}

/**
 * Display shortcodes page
 */
function infinity_dashboard_cpanel_shortcodes_content()
{
	infinity_dashboard_load_template( 'cpanel/shortcodes.php' );
}

/**
 * Display options form
 */
function infinity_dashboard_cpanel_options_content()
{
	infinity_dashboard_load_template( 'cpanel/options.php' );
}

/**
 * Display docs page
 */
function infinity_dashboard_cpanel_docs_content()
{
	infinity_dashboard_load_template( 'cpanel/docs.php' );
}

/**
 * Display about page
 */
function infinity_dashboard_cpanel_about_content()
{
	infinity_dashboard_load_template( 'cpanel/about.php' );
}

/**
 * Display thanks page
 */
function infinity_dashboard_cpanel_thanks_content()
{
	infinity_dashboard_load_template( 'cpanel/thanks.php' );
}

?>
