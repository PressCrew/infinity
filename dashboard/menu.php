<?php
/**
 * Infinity Theme: dashboard menu functions
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
add_action( 'admin_menu', 'infinity_dashboard_menu_setup' );
////

/**
 * Adds the Infinity submenu item to the WordPress menu
 */
function infinity_dashboard_menu_setup()
{
	add_menu_page(
		__( 'Infinity', infinity_text_domain),
		__( 'infinity', infinity_text_domain),
		'manage_options',
		INFINITY_ADMIN_PAGE,
		'infinity_dashboard_cpanel_screen',
		INFINITY_ADMIN_URL . '/assets/images/icon_16.png',
		1 );

	add_action( 'admin_print_styles', 'infinity_dashboard_menu_styler', 99999 );
	add_action( 'admin_print_scripts', 'infinity_dashboard_menu_shifter', 99999 );
}

/**
 * Make admin menu pretty
 */
function infinity_dashboard_menu_selector()
{
	return 'toplevel_page_' . INFINITY_ADMIN_PAGE;
}

/**
 * Make admin menu pretty
 */
function infinity_dashboard_menu_styler()
{
	// print it ?>
<style type="text/css">
#adminmenu li#<?php print infinity_dashboard_menu_selector() ?> a.<?php print infinity_dashboard_menu_selector() ?> {
	color: #285D92; font-family: "Droid Sans","Arial","sans-serif"; font-weight: bold; text-shadow: -1px -1px #eeeeee, 2px 1px #c2c2c2; letter-spacing: 0.16em;
	border-color: #dedede; background: #ccc url(<?php print INFINITY_ADMIN_URL ?>/assets/images/ui_pattern_bg.png) repeat 0px -3px;
}
</style><?php
}

/**
 * Make admin menu our b*tch
 */
function infinity_dashboard_menu_shifter()
{
	// print it ?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('li.wp-menu-separator').first().clone().insertAfter(
		jQuery('li#<?php print infinity_dashboard_menu_selector() ?>').addClass('menu-top-first menu-top-last').insertBefore('li#menu-posts'));
});
</script><?php
}

?>
