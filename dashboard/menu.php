<?php
/**
 * Infinity theme dashboard menu functions
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
 * Adds the Infinity submenu item to the BuddyPress menu
 */
function infinity_dashboard_setup_menu()
{
	// PIE menu
	add_submenu_page(
		'bp-general-settings',
		__( 'PIE', INFINITY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="infinity-menu-icon" />', infinity_dashboard_image('pie_16x16.png') ) . __( 'PIE', INFINITY_TEXT_DOMAIN ),
		'manage_options',
		'infinity-pie-panel',
		'infinity_dashboard_pie_screen' );

	// Sweet control panel
	add_submenu_page(
		'bp-general-settings',
		__( 'Sweet', INFINITY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="infinity-menu-icon indent" />', infinity_dashboard_image('sweet_16x16.png') ) . __( 'Sweet API', INFINITY_TEXT_DOMAIN ),
		'manage_options',
		'infinity-sweet-panel',
		'infinity_dashboard_sweet_screen' );

	// Infinity control panel
	add_submenu_page(
		'bp-general-settings',
		__( 'Infinity', INFINITY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="infinity-menu-icon indent" />', infinity_dashboard_image('infinity_16x16.png') ) . __( 'Infinity Theme', INFINITY_TEXT_DOMAIN ),
		'manage_options',
		'infinity-control-panel',
		'infinity_dashboard_cpanel_screen' );
}

?>
