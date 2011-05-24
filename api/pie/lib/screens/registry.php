<?php
/**
 * PIE API: screens registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'screens/factory' );

/**
 * Make keeping track of screens easy
 *
 * @package PIE
 * @subpackage screens
 */
abstract class Pie_Easy_Screens_Registry extends Pie_Easy_Registry
{
	/**
	 * Load a screen into the registry
	 *
	 * @param string $screen_name
	 * @param string $screen_config
	 * @return boolean
	 */
	protected function load_config_single( $screen_name, $screen_config )
	{
		// create new screen
		$screen = $this->policy()->factory()->create(
			$screen_config['type'],
			$this->loading_theme,
			$screen_name,
			$screen_config['title']
		);

		// parent
		if ( isset( $screen_config['parent'] ) ) {
			$screen->set_parent( $screen_config['parent'] );
		}

		// icons
		$icon_primary = ( isset( $screen_config['icon_primary'] ) ) ? $screen_config['icon_primary'] : null;
		$icon_secondary = ( isset( $screen_config['icon_secondary'] ) ) ? $screen_config['icon_secondary'] : null;
		$screen->icon( new Pie_Easy_Icon( $icon_primary, $icon_secondary ) );

		// show on toolbar?
		if ( isset( $screen_config['toolbar'] ) ) {
			$screen->set_toolbar( $screen_config['toolbar'] );
		}

		// template
		if ( isset( $screen_config['template'] ) ) {
			$screen->set_template( $screen_config['template'] );
		}

		// register it
		return $this->register( $screen );
	}

}

?>
