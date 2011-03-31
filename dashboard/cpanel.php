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

	if ( ($route) && $route['screen'] == 'cpanel' ) {
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
 *
 * @param array|stdClass Variables to inject into template
 */
function infinity_dashboard_cpanel_options_content( $args = null )
{
	$defaults->menu_args = null;

	infinity_dashboard_load_template( 'cpanel/options.php', $args, $defaults );
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
		Pie_Easy_Ajax::responseStd( false, sprintf( __( 'There was an error while trying to load the %s tab content.', INFINITY_TEXT_DOMAIN ), $action ) );
	}
}
add_action( 'wp_ajax_infinity_tabs_content', 'infinity_dashboard_cpanel_tabs_content' );

?>
