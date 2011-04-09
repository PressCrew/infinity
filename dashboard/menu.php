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
	add_menu_page(
		__( 'Infinity', INFINITY_TEXT_DOMAIN),
		__( 'infinity', INFINITY_TEXT_DOMAIN),
		'manage_options',
		INFINITY_ADMIN_PAGE,
		'infinity_dashboard_cpanel_screen',
		INFINITY_ADMIN_URL . '/assets/images/icon_16.png',
		-837 );

	add_action( 'admin_print_scripts', 'infinity_dashboard_menu_shifter', 99999 );
}

/**
 * Make admin menu our b*tch
 */
function infinity_dashboard_menu_shifter()
{
	// print it ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var m = jQuery('li#toplevel_page_infinity-theme')
				.addClass('menu-top-first menu-top-last')
				.insertBefore('li#menu-posts');
		m.children('a').first().css({
			'color': '#3F7CAB',
			'background': '#cccccc url(<?php print INFINITY_ADMIN_URL ?>/assets/images/ui_pattern_bg.png) repeat 0px -3px',
			'font-family': 'Helvetica,Arial,sans-serif',
			'font-weight': 'bold',
			'letter-spacing': '0.16em',
			'border-color': '#dddddd'
		});
		jQuery('li.wp-menu-separator').first().clone().insertAfter(m);
	});
</script><?php
}

?>
