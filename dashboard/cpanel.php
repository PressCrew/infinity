<?php
/**
 * BP Tasty theme dashboard control panel functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage control-panel
 * @since 1.0
 */

function bp_tasty_dashboard_action()
{
	if ( isset( $_GET['action'] ) ) {
		return trim( $_GET['action'] );
	} elseif ( isset( $_POST['action'] ) ) {
		return trim( $_POST['action'] );
	} else {
		return key( bp_tasty_dashboard_actions() );
	}
}

/**
 * Return actions configuration array
 *
 * @return array
 */
function bp_tasty_dashboard_actions()
{
	return
		array(
			'start' => __( 'Start', BP_TASTY_TEXT_DOMAIN ),
			'skins' => __( 'Skins', BP_TASTY_TEXT_DOMAIN ),
			'widgets' => __( 'Widgets', BP_TASTY_TEXT_DOMAIN ),
			'shortcodes' => __( 'Shortcodes', BP_TASTY_TEXT_DOMAIN ),
			'options' => __( 'Options', BP_TASTY_TEXT_DOMAIN ),
			'docs' => __( 'Documentation', BP_TASTY_TEXT_DOMAIN ),
			'about' => __( 'About', BP_TASTY_TEXT_DOMAIN ),
			'thanks' => __( 'Thanks', BP_TASTY_TEXT_DOMAIN )
		);
}

/**
 * Print the navigation
 */
function bp_tasty_dashboard_cpanel_navigation()
{
	$actions = bp_tasty_dashboard_actions();
	$current_action = bp_tasty_dashboard_action();
	$cell_width = floor( 100 / count($actions) ); ?>

	<table class="widefat bp-tasty-cpanel-nav">
		<tr><?php
		foreach ( $actions as $action_slug => $action_title ):
			$current_class = ( $action_slug == $current_action ) ? 'current' : '' ?>
			<td class="<?php print $current_class ?>" style="width: <?php print $cell_width ?>%;"><a href="?page=bp-tasty-control-panel&action=<?php print $action_slug ?>" class="bp-tasty-cpanel-page-<?php print $action_slug ?>"><?php print $action_title ?></a></td><?php
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
function bp_tasty_dashboard_cpanel_screen()
{
	bp_tasty_dashboard_load_template( 'layout.php' );
}

/**
 * Route requests and display the control panel
 */
function bp_tasty_dashboard_pie_screen()
{
	bp_tasty_dashboard_load_template( 'pie.php' );
}

/**
 * Route requests and display the control panel
 */
function bp_tasty_dashboard_sweet_screen()
{
	bp_tasty_dashboard_load_template( 'sweet.php' );
}

//
// Content Actions
//

/**
 * Display options form
 */
function bp_tasty_dashboard_cpanel_options_content()
{
	bp_tasty_dashboard_load_template( 'options.php' );
}

?>
