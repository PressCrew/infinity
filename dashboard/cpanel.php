<?php
/**
 * Infinity theme dashboard control panel functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage control-panel
 * @since 1.0
 */

/**
 * Return the current dashboard action
 *
 * @return string|null
 */
function infinity_dashboard_cpanel_action()
{
	if ( $_GET['page'] == INFINITY_ADMIN_PAGE ) {
		if ( isset( $_GET['action'] ) ) {
			return trim( $_GET['action'] );
		} elseif ( isset( $_POST['action'] ) ) {
			return trim( $_POST['action'] );
		} else {
			return key( infinity_dashboard_cpanel_actions() );
		}
	}

	return null;
}

/**
 * Return actions configuration array
 *
 * @return array
 */
function infinity_dashboard_cpanel_actions()
{
	return
		array(
			'start' => __( 'Start', INFINITY_TEXT_DOMAIN ),
			'widgets' => __( 'Widgets', INFINITY_TEXT_DOMAIN ),
			'shortcodes' => __( 'Shortcodes', INFINITY_TEXT_DOMAIN ),
			'options' => __( 'Options', INFINITY_TEXT_DOMAIN ),
			'docs' => __( 'Documentation', INFINITY_TEXT_DOMAIN ),
			'about' => __( 'About', INFINITY_TEXT_DOMAIN ),
			'thanks' => __( 'Thanks', INFINITY_TEXT_DOMAIN )
		);
}

/**
 * Print the navigation
 */
function infinity_dashboard_cpanel_navigation()
{
	$actions = infinity_dashboard_cpanel_actions();
	$current_action = infinity_dashboard_cpanel_action();
	$cell_width = floor( 100 / count($actions) ); ?>

	<table class="widefat infinity-cpanel-nav">
		<tr><?php
		foreach ( $actions as $action_slug => $action_title ):
			$current_class = ( $action_slug == $current_action ) ? 'current' : '' ?>
			<td class="<?php print $current_class ?>" style="width: <?php print $cell_width ?>%;"><a href="?page=<?php print INFINITY_ADMIN_PAGE ?>&action=<?php print $action_slug ?>" class="infinity-cpanel-page-<?php print $action_slug ?>"><?php print $action_title ?></a></td><?php
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
function infinity_dashboard_cpanel_screen()
{
	infinity_dashboard_load_template( 'layout.php' );
}

//
// Content Actions
//

/**
 * Display options form
 */
function infinity_dashboard_cpanel_options_content()
{
	infinity_dashboard_load_template( 'options.php' );
}

?>
