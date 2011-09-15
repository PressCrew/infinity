<?php
/**
 * PIE API: widget extensions, menu widget class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage widgets
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/widgets/component', 'utils/ajax', 'utils/files' );

/**
 * Menu widget
 *
 * @package PIE-extensions
 * @subpackage widgets
 */
class Pie_Easy_Exts_Widgets_Menu
	 extends Pie_Easy_Widgets_Widget
{
	protected function init()
	{
		parent::init();

		// requires ui menu
		$this->script()->add_dep( 'jquery-ui-menu' );
	}

	public function configure( $config, $theme )
	{
		// RUN PARENT FIRST!
		parent::configure( $config, $theme );

		// css title class
		if ( isset( $config['menu_items'] ) ) {
			$this->directives()->set( $theme, 'menu_items', $config['menu_items'] );
		}
	}

	protected function get_items()
	{
		$callback = $this->directive('menu_items')->value;

		if ( function_exists( $callback ) ) {
			return call_user_func( $callback );
		} else {
			throw new Exception(
				sprintf( 'The menu items callback function "%" does not exist', $callback ) );
		}
	}

	public function get_menu_id()
	{
		return 'pie-easy-exts-widget-menu-' . esc_attr( $this->name );
	}

	public function get_menu_item_id( $slug )
	{
		return 'pie-easy-exts-widget-menu-item-' . esc_attr( $slug );
	}

	public function get_menu_buttons_func()
	{
		return 'widgetMenuInitButtons_' . str_replace('-', '_', $this->name );
	}

	public function render_items()
	{
		foreach( $this->get_items() as $item_slug => $item ) { ?>
			<li>
				<a id="<?php print $this->get_menu_item_id( $item_slug ) ?>" href="<?php print esc_attr( $item['href'] ) ?>"><?php print esc_attr( $item['text'] ) ?></a>
			</li><?php
		} ?>
		</ul><?php
	}

	public function export_script()
	{
		$items = $this->get_items();

		// start script
		$this->script()->begin_logic();

		// loop all items
		foreach( $this->get_items() as $item_slug => $item ) {
			// reset vars
			$conf = null;
			$icon_primary = null;
			$icon_secondary = null;
			// check for primary
			if ( isset( $item['icon_primary'] ) ) {
				$icon_primary = $item['icon_primary'];
			}
			// check for secondary
			if ( isset( $item['icon_secondary'] ) ) {
				$icon_secondary = $item['icon_secondary'];
			}
			// get any?
			if ( $icon_primary || $icon_secondary) {
				// new icon object
				$icon = new Pie_Easy_Icon( $icon_primary, $icon_secondary );
				// format conf
				$conf = sprintf( '{%s}', $icon->config() );
			}
			// render the button script ?>
			jQuery('a#<?php print $this->get_menu_item_id( $item_slug ) ?>').button(<?php print $conf ?>); <?php
		}

		// capture the logic object
		$logic = $this->script()->end_logic();

		// wrap logic in a function
		$logic->function = $this->get_menu_buttons_func();

		// run parent when done, stupid important
		return parent::export_script();
	}
}

?>
