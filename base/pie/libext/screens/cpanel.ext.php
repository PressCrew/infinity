<?php
/**
 * PIE API: screen extensions, cpanel screen class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage screens-ext
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/iconable', 'base/positionable' );

/**
 * Cpanel screen
 *
 * @package PIE
 * @subpackage screens-ext
 */
class Pie_Easy_Exts_Screen_Cpanel
	extends Pie_Easy_Screens_Screen
		implements Pie_Easy_Iconable, Pie_Easy_Positionable
{
	/**
	 * The icon object
	 * 
	 * @var Pie_Easy_Icon
	 */
	private $icon;

	/**
	 * The position object
	 *
	 * @var Pie_Easy_Position
	 */
	private $position;

	/**
	 * Set/Return the icon object
	 *
	 * @param Pie_Easy_Icon $icon
	 * @return Pie_Easy_Icon
	 */
	public function icon( Pie_Easy_Icon $icon = null )
	{
		if ( $icon ) {
			$this->icon = $icon;
		}

		return $this->icon;
	}

	/**
	 * Set/Return the position object
	 *
	 * @param Pie_Easy_Position $position
	 * @return Pie_Easy_Position
	 */
	public function position( Pie_Easy_Position $position = null )
	{
		if ( $position ) {
			$this->position = $position;
		}

		return $this->position;
	}

	/**
	 * Set toolbar toggle
	 *
	 * @param boolean $toggle
	 */
	public function set_toolbar( $toggle )
	{
		$this->directives()->set( $this->theme, 'toolbar', $toggle );
	}
}

?>
