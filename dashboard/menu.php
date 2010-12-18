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
function infinity_dashboard_menu_setup()
{
	add_object_page(
		__( 'Infinity', INFINITY_TEXT_DOMAIN),
		__( 'Infinity', INFINITY_TEXT_DOMAIN),
		'manage_options',
		INFINITY_ADMIN_PAGE,
		'infinity_dashboard_cpanel_screen' );
}

?>
