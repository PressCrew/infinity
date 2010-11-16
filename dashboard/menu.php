<?php
/**
 * BP Tasty theme dashboard menu functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage menu
 * @since 1.0
 */

/**
 * Adds the Tasty Framework submenu item to the BuddyPress menu
 */
function bp_tasty_dashboard_setup_menu()
{
	// PIE menu
	add_submenu_page(
		'bp-general-settings',
		__( 'PIE Framework', BP_TASTY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="bp-tasty-menu-icon" />', bp_tasty_dashboard_image('pie_16x16.png') ) . __( 'PIE Framework', BP_TASTY_TEXT_DOMAIN ),
		'manage_options',
		'bp-tasty-pie-panel',
		'bp_tasty_dashboard_pie_screen' );

	// Sweet control panel
	add_submenu_page(
		'bp-general-settings',
		__( 'Sweet', BP_TASTY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="bp-tasty-menu-icon indent" />', bp_tasty_dashboard_image('sweet_16x16.png') ) . __( 'Sweet API', BP_TASTY_TEXT_DOMAIN ),
		'manage_options',
		'bp-tasty-sweet-panel',
		'bp_tasty_dashboard_sweet_screen' );

	// Tasty control panel
	add_submenu_page(
		'bp-general-settings',
		__( 'Tasty', BP_TASTY_TEXT_DOMAIN),
		sprintf('<img src="%s" class="bp-tasty-menu-icon indent" />', bp_tasty_dashboard_image('tasty_16x16.png') ) . __( 'Tasty Theme', BP_TASTY_TEXT_DOMAIN ),
		'manage_options',
		'bp-tasty-control-panel',
		'bp_tasty_dashboard_cpanel_screen' );
}

?>
