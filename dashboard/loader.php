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
define( 'INFINITY_ADMIN_TPLS_DIR', INFINITY_ADMIN_DIR . '/templates' );

//
// Files
//
require_once( INFINITY_ADMIN_DIR . '/menu.php' );
require_once( INFINITY_ADMIN_DIR . '/cpanel.php' );

//
// Actions
//
add_action( 'admin_init', 'infinity_ajax_setup' );
add_action( 'admin_menu', 'infinity_dashboard_setup' );
add_action( 'admin_menu', 'infinity_dashboard_setup_menu' );
add_action( 'infinity_dashboard_cpanel_content', 'infinity_dashboard_cpanel_options_content' );

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
function infinity_dashboard_setup()
{
	// enqueue styles
    wp_enqueue_style( 'infinity-dashboard', INFINITY_ADMIN_URL . '/assets/css/cpanel.css', false, INFINITY_VERSION, 'screen' );

	// enqueue script
	wp_enqueue_script( 'infinity-dashboard', INFINITY_ADMIN_URL . '/assets/js/dashboard.js', false, INFINITY_VERSION );
	
	if ( $_GET['page'] == 'infinity-control-panel' ) {
		// pie easy options init
		Infinity_Options::init();
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
 * Return path to a dashboard image
 */
function infinity_dashboard_image( $name )
{
	return INFINITY_ADMIN_URL . '/assets/images/' . $name;
}

?>
