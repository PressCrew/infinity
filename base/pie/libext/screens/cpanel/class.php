<?php
/**
 * PIE API: screen extensions, cpanel screen class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/iconable', 'ui/positionable' );

/**
 * Cpanel screen
 *
 * @package PIE-extensions
 * @subpackage screens
 */
class Pie_Easy_Exts_Screens_Cpanel
	extends Pie_Easy_Screens_Screen
		implements Pie_Easy_Iconable, Pie_Easy_Positionable
{
	/**
	 * The icon object
	 * 
	 * @var Pie_Easy_Icon
	 */
	private $__icon__;

	/**
	 * The position object
	 *
	 * @var Pie_Easy_Position
	 */
	private $__position__;

	public function configure( $config, $theme )
	{
		// RUN PARENT FIRST!
		parent::configure( $config, $theme );

		// icons
		$icon_primary = ( isset( $config['icon_primary'] ) ) ? $config['icon_primary'] : null;
		$icon_secondary = ( isset( $config['icon_secondary'] ) ) ? $config['icon_secondary'] : null;

		if ( $this->icon() ) {
			if ( $icon_primary ) {
				$this->icon()->primary = $icon_primary;
			}
			if ( $icon_secondary ) {
				$this->icon()->secondary = $icon_secondary;
			}
		} else {
			$this->icon( new Pie_Easy_Icon( $icon_primary, $icon_secondary ) );
		}

		// priority
		if ( isset( $config['priority'] ) ) {
			$this->position( new Pie_Easy_Position( $config['priority'] ) );
		}

		// show on toolbar?
		if ( isset( $config['toolbar'] ) ) {
			$this->directives()->set( $theme, 'toolbar', (boolean) $config['toolbar'] );
		}
	}

	/**
	 * Set/Return the icon object
	 *
	 * @param Pie_Easy_Icon $icon
	 * @return Pie_Easy_Icon
	 */
	final public function icon( Pie_Easy_Icon $icon = null )
	{
		if ( $icon ) {
			$this->__icon__ = $icon;
		}

		return $this->__icon__;
	}

	/**
	 * Set/Return the position object
	 *
	 * @param Pie_Easy_Position $position
	 * @return Pie_Easy_Position
	 */
	final public function position( Pie_Easy_Position $position = null )
	{
		if ( $position ) {
			$this->__position__ = $position;
		}

		return $this->__position__;
	}
}

?>
