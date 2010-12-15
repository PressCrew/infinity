<?php
/**
 * Tasty theme dashboard loader
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
define( 'TASTY_ADMIN_TPLS_DIR', TASTY_ADMIN_DIR . '/templates' );

//
// Files
//
require_once( TASTY_ADMIN_DIR . '/menu.php' );
require_once( TASTY_ADMIN_DIR . '/cpanel.php' );

//
// Actions
//
add_action( 'admin_init', 'tasty_ajax_setup' );
add_action( 'admin_menu', 'tasty_dashboard_setup' );
add_action( 'admin_menu', 'tasty_dashboard_setup_menu' );
add_action( 'tasty_dashboard_cpanel_content', 'tasty_dashboard_cpanel_options_content' );

//
// Functions
//

/**
 * Setup AJAX handling
 */
function tasty_ajax_setup()
{
	if ( defined( 'DOING_AJAX' ) ) {
		Tasty_Options::init_ajax();
	}
}

/**
 * Handle setup of the control panel environment
 */
function tasty_dashboard_setup()
{
	// enqueue styles
    wp_enqueue_style( 'tasty-dashboard', TASTY_ADMIN_URL . '/assets/css/cpanel.css', false, TASTY_VERSION, 'screen' );

	// enqueue script
	wp_enqueue_script( 'tasty-dashboard', TASTY_ADMIN_URL . '/assets/js/dashboard.js', false, TASTY_VERSION );
	
	if ( $_GET['page'] == 'tasty-control-panel' ) {
		// pie easy options init
		Tasty_Options::init();
	}
}

/**
 * Load a dashboard template relative to the template dir root
 *
 * @param string $rel_path
 */
function tasty_dashboard_load_template( $rel_path )
{
	include( TASTY_ADMIN_TPLS_DIR . DIRECTORY_SEPARATOR . $rel_path );
}

/**
 * Return path to a dashboard image
 */
function tasty_dashboard_image( $name )
{
	return TASTY_ADMIN_URL . '/assets/images/' . $name;
}

?>
