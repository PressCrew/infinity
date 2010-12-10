<?php
/**
 * BP Tasty theme dashboard control panel functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage control-panel
 * @since 1.0
 */

function tasty_dashboard_action()
{
	if ( isset( $_GET['action'] ) ) {
		return trim( $_GET['action'] );
	} elseif ( isset( $_POST['action'] ) ) {
		return trim( $_POST['action'] );
	} else {
		return key( tasty_dashboard_actions() );
	}
}

/**
 * Return actions configuration array
 *
 * @return array
 */
function tasty_dashboard_actions()
{
	return
		array(
			'start' => __( 'Start', TASTY_TEXT_DOMAIN ),
			'skins' => __( 'Skins', TASTY_TEXT_DOMAIN ),
			'widgets' => __( 'Widgets', TASTY_TEXT_DOMAIN ),
			'shortcodes' => __( 'Shortcodes', TASTY_TEXT_DOMAIN ),
			'options' => __( 'Options', TASTY_TEXT_DOMAIN ),
			'docs' => __( 'Documentation', TASTY_TEXT_DOMAIN ),
			'about' => __( 'About', TASTY_TEXT_DOMAIN ),
			'thanks' => __( 'Thanks', TASTY_TEXT_DOMAIN )
		);
}

/**
 * Print the navigation
 */
function tasty_dashboard_cpanel_navigation()
{
	$actions = tasty_dashboard_actions();
	$current_action = tasty_dashboard_action();
	$cell_width = floor( 100 / count($actions) ); ?>

	<table class="widefat tasty-cpanel-nav">
		<tr><?php
		foreach ( $actions as $action_slug => $action_title ):
			$current_class = ( $action_slug == $current_action ) ? 'current' : '' ?>
			<td class="<?php print $current_class ?>" style="width: <?php print $cell_width ?>%;"><a href="?page=tasty-control-panel&action=<?php print $action_slug ?>" class="tasty-cpanel-page-<?php print $action_slug ?>"><?php print $action_title ?></a></td><?php
		endforeach; ?>
		</tr>
	</table><?php
}

//
// Screens
//

/**
 * Route requests and display the control panel
 */
function tasty_dashboard_cpanel_screen()
{
	tasty_dashboard_load_template( 'layout.php' );
}

/**
 * Route requests and display the control panel
 */
function tasty_dashboard_pie_screen()
{
	tasty_dashboard_load_template( 'pie.php' );
}

/**
 * Route requests and display the control panel
 */
function tasty_dashboard_sweet_screen()
{
	tasty_dashboard_load_template( 'sweet.php' );
}

//
// Content Actions
//

/**
 * Display options form
 */
function tasty_dashboard_cpanel_options_content()
{
	tasty_dashboard_load_template( 'options.php' );
}

?>
