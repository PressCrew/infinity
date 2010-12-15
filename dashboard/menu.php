<?php
/**
 * Tasty theme dashboard menu functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage menu
 * @since 1.0
 */

/**
 * Adds the Tasty submenu item to the BuddyPress menu
 */
function tasty_dashboard_setup_menu()
{
	// PIE menu
	add_submenu_page(
		'bp-general-settings',
		__( 'PIE', TASTY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="tasty-menu-icon" />', tasty_dashboard_image('pie_16x16.png') ) . __( 'PIE', TASTY_TEXT_DOMAIN ),
		'manage_options',
		'tasty-pie-panel',
		'tasty_dashboard_pie_screen' );

	// Sweet control panel
	add_submenu_page(
		'bp-general-settings',
		__( 'Sweet', TASTY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="tasty-menu-icon indent" />', tasty_dashboard_image('sweet_16x16.png') ) . __( 'Sweet API', TASTY_TEXT_DOMAIN ),
		'manage_options',
		'tasty-sweet-panel',
		'tasty_dashboard_sweet_screen' );

	// Tasty control panel
	add_submenu_page(
		'bp-general-settings',
		__( 'Tasty', TASTY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="tasty-menu-icon indent" />', tasty_dashboard_image('tasty_16x16.png') ) . __( 'Tasty Theme', TASTY_TEXT_DOMAIN ),
		'manage_options',
		'tasty-control-panel',
		'tasty_dashboard_cpanel_screen' );
}

?>
