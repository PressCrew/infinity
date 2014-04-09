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
		// hook for config actions
		do_action( 'infinity_dashboard_cpanel_setup' );
	}
}

/**
 * Handle registration of cpanel assets.
 *
 * @package Infinity
 * @subpackage dashboard
 */
function infinity_dashboard_cpanel_assets()
{
	// cpanel styles
	ice_register_style(
		'infinity-cpanel',
		array(
			'src' => INFINITY_THEME_URL . '/dashboard/assets/css/cpanel.css',
			'deps' => array( 'ice-ui' ),
			'action' => 'admin_print_styles-appearance_page_infinity-theme',
			'condition' => 'is_admin'
		)
	);

	// options styles
	ice_register_style(
		'infinity-options',
		array(
			'src' => INFINITY_THEME_URL . '/dashboard/assets/css/options.css',
			'action' => 'admin_print_styles-appearance_page_infinity-theme',
			'condition' => 'is_admin'
		)
	);

	// docs styles
	ice_register_style(
		'infinity-docs',
		array(
			'src' => INFINITY_THEME_URL . '/dashboard/assets/css/docs.css',
			'action' => 'admin_print_styles-appearance_page_infinity-theme',
			'condition' => 'is_admin'
		)
	);

	// cpanel script
	ice_register_script(
		'cpanel',
		array(
			'src' => INFINITY_THEME_URL . '/dashboard/assets/js/cpanel.js',
			'deps' => array(
				'ice-global',
				'jquery-cookie',
				'jquery-ui-accordion',
				'jquery-ui-button',
				'jquery-ui-dialog',
				'jquery-ui-position',
				'jquery-ui-progressbar',
				'jquery-ui-sortable',
				'jquery-ui-resizable',
				'jquery-ui-tabs',
				'jquery-juicy-buttonmenu',
				'jquery-juicy-flashmesg',
				'jquery-juicy-toolbar'
			),
			'in_footer' => true,
			'action' => 'admin_print_scripts-appearance_page_infinity-theme',
			'condition' => 'is_admin'
		)
	);

	// docs script
	ice_register_script(
		'docs',
		array(
			'src' => INFINITY_THEME_URL . '/dashboard/assets/js/docs.js',
			'deps' => array( 'jquery' ),
			'in_footer' => true,
			'action' => 'admin_print_scripts-appearance_page_infinity-theme',
			'condition' => 'is_admin'
		)
	);

}
add_action( 'infinity_dashboard_cpanel_setup', 'infinity_dashboard_cpanel_assets' );

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
