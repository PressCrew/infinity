<?php
/**
 * PIE API: screens factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/factory' );

/**
 * Make creating screen objects easy
 *
 * @package PIE
 * @subpackage screens
 */
class Pie_Easy_Screens_Factory extends Pie_Easy_Factory
{
	/**
	 * Return an instance of a screen component
	 *
	 * @param string $theme
	 * @param string $name
	 * @param array $config
	 * @return Pie_Easy_Screens_Screen
	 */
	public function create( $theme, $name, $config )
	{
		$screen = parent::create( $theme, $name, $config );

		// icons
		$icon_primary = ( isset( $config['icon_primary'] ) ) ? $config['icon_primary'] : null;
		$icon_secondary = ( isset( $config['icon_secondary'] ) ) ? $config['icon_secondary'] : null;
		$screen->icon( new Pie_Easy_Icon( $icon_primary, $icon_secondary ) );

		// priority
		if ( isset( $config['priority'] ) ) {
			$screen->position( new Pie_Easy_Position( $config['priority'] ) );
		}

		// show on toolbar?
		if ( isset( $config['toolbar'] ) ) {
			$screen->set_toolbar( $config['toolbar'] );
		}

		return $screen;
	}
}

?>
