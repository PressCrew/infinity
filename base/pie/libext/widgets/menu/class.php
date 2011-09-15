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
	public function init_scripts()
	{
		parent::init_scripts();
		wp_enqueue_script( 'jquery-ui-menu' );
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

	public function get_template_vars()
	{
		return array(
			'element_id' => $this->get_menu_id(),
			'button_script' => $this->get_button_script()
		);
	}

	/**
	 * Execute configured menu_items callback and return results
	 *
	 * @return array
	 */
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

	/**
	 * Format and return the menu element id
	 *
	 * @return string
	 */
	protected function get_menu_id()
	{
		return 'pie-easy-exts-widget-menu-' . esc_attr( $this->name );
	}

	/**
	 * Format and return a menu item element id
	 *
	 * @return string
	 */
	protected function get_menu_item_id( $slug )
	{
		return 'pie-easy-exts-widget-menu-item---' . esc_attr( $slug );
	}

	/**
	 * Render the list element that will be menufied
	 */
	public function render_items()
	{
		// render list
		foreach( $this->get_items() as $item_slug => $item ) { ?>
			<li>
				<a id="<?php print $this->get_menu_item_id( $item_slug ) ?>" href="<?php print esc_attr( $item['href'] ) ?>"><?php print esc_attr( $item['text'] ) ?></a>
			</li><?php
		}
	}

	/**
	 * Generate all script for creating buttons and return script object
	 *
	 * @return Pie_Easy_Script
	 */
	protected function get_button_script()
	{
		// script object
		$script = new Pie_Easy_Script();

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

			// start capturing
			$script->begin_logic();
			
			// the button statement (one liner) ?>
			jQuery('a#<?php print $this->get_menu_item_id( $item_slug ) ?>').button(<?php print $conf ?>); <?php

			// end capturing
			$script->end_logic();
		}

		return $script;
	}
}

?>
