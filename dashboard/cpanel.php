<?php
/**
 * Infinity Theme: dashboard control panel functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package dashboard
 * @subpackage control-panel
 * @since 1.0
 */

//
// Hooks
//
add_action( 'admin_init', 'infinity_dashboard_cpanel_setup' );
////

/**
 * Return the current dashboard action
 *
 * @return string|null
 */
function infinity_dashboard_cpanel_action()
{
	$route = infinity_dashboard_route_parse();

	if ( ($route) && $route['screen'] == 'cpanel' ) {
		if ( $route['action'] ) {
			return $route['action'];
		} else {
			return array_shift( infinity_dashboard_cpanel_actions() );
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
	return array(
		// main
		'start', 'options', 'shortcodes', 'widgets', 'about',
		// devs
		'docs', 'api',
		// community
		'news', 'thanks'
	);
}

//
// Actions
//

/**
 * Handle setup of the control panel environment
 */
function infinity_dashboard_cpanel_setup()
{
	// setup dashboard if its active
	$action = infinity_dashboard_cpanel_action();

	if ( $action ) {

		// init options
		infinity_scheme_init();

		// init options
		infinity_options_init();

		// init options screens
		infinity_options_init_screen();

		// init features
		$feature_policy = Infinity_Features_Policy::instance();
		Pie_Easy_Scheme::instance()->enable_component( $feature_policy );

		// init screens
		$screen_policy = Infinity_Screens_Policy::instance();
		Pie_Easy_Scheme::instance()->enable_component( $screen_policy );

		// tab action
		add_action( 'wp_ajax_infinity_tabs_content', 'infinity_dashboard_cpanel_tabs_content' );

		// hook for config actions
		do_action( 'infinity_dashboard_cpanel_setup' );
	}
}

/**
 * Output cpanel tab content
 */
function infinity_dashboard_cpanel_tabs_content()
{
	$action = infinity_dashboard_cpanel_action();
	$screen = Infinity_Screens_Policy::instance()->registry()->get( $action );

	if ( $screen instanceof Pie_Easy_Screens_Screen ) {
		Pie_Easy_Ajax::responseBegin();
		$screen->render();
		Pie_Easy_Ajax::responseEnd( true );
	} else {
		Pie_Easy_Ajax::responseStd( false, sprintf( __('There was an error while trying to load the %s tab content.', infinity_text_domain), $action ) );
	}
}

//
// Screens
//

/**
 * Route requests and display the control panel
 */
function infinity_dashboard_cpanel_screen()
{
	infinity_dashboard_load_template( 'cpanel.php' );
}

/**
 * Print cpanel dropdown menu
 */
function infinity_dashboard_cpanel_toolbar_menu( $items = null )
{
	if ( empty( $items ) ) {
		$items = Infinity_Screens_Policy::instance()->registry()->get_roots(); ?>
		<ul id="infinity-cpanel-toolbar-menu-items"><?php
	} else { ?>
		<ul><?php
	}

	// sort em
	$items = Pie_Easy_Position::sort_priority( $items );

	foreach( $items as $item ) {
		$children = Infinity_Screens_Policy::instance()->registry()->get_children( $item );
		$children_cnt = count( $children ); ?>
		<li>
			<a id="infinity-cpanel-toolbar-menu-item-<?php print esc_attr( $item->name ) ?>"<?php if ( $children_cnt ): ?> class="infinity-cpanel-context-menu"<?php endif; ?> href="<?php print infinity_dashboard_route( 'cpanel', $item->name ) ?>#infinity-cpanel-tab-<?php print esc_attr( $item->name ) ?>" title="<?php print esc_attr( $item->title ) ?>"><?php print esc_attr( $item->title ) ?></a>
			<?php if ( $children_cnt ): ?>
				<?php infinity_dashboard_cpanel_toolbar_menu( $children ) ?>
			<?php endif; ?>
		</li><?php
	} ?>
	</ul><?php
}

/**
 * Print cpanel quick buttons
 */
function infinity_dashboard_cpanel_toolbar_buttons()
{
	$items = Infinity_Screens_Policy::instance()->registry()->get_all();

	foreach( $items as $item ): ?>
		<?php if ( $item->toolbar ): ?>
			<a id="infinity-cpanel-toolbar-<?php print esc_attr( $item->name ) ?>" href="<?php print infinity_dashboard_route( 'cpanel', $item->name ) ?>#infinity-cpanel-tab-<?php print esc_attr( $item->name ) ?>" title="<?php print esc_attr( $item->title ) ?>"><?php print esc_attr( $item->title ) ?></a>
		<?php endif;
	endforeach;
}

/**
 * Print cpanel dropdown menu javascript
 */
function infinity_dashboard_cpanel_dynamic_scripts()
{
	$items = Infinity_Screens_Policy::instance()->registry()->get_all();

	// begin rendering ?>
	<script type="text/javascript">
		(function($){
			$(document).ready(function() {
			<?php foreach( $items as $item ):
				$conf = null;
				$icons = $item->icon()->config();
				if ( $icons ):
					$conf = sprintf( '{%s}', $icons );
				endif; ?>
				$('a#infinity-cpanel-toolbar-menu-item-<?php print $item->name ?>').button(<?php print $conf ?>);
				<?php if ( $item->toolbar ): ?>
					$('a#infinity-cpanel-toolbar-<?php print $item->name ?>').button(<?php print $conf ?>);
				<?php endif; ?>
			<?php endforeach; ?>
			});
		})(jQuery);
	</script><?php
}
add_action( 'admin_print_footer_scripts', 'infinity_dashboard_cpanel_dynamic_scripts' );

/**
 * Display options form
 *
 * @param array|stdClass Variables to inject into template
 */
function infinity_dashboard_cpanel_options_content( $args = null )
{
	$defaults->menu_args = null;

	infinity_dashboard_load_template( 'cpanel/options.php', $args, $defaults );
}

?>
