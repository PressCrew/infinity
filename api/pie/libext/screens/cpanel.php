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
		implements Pie_Easy_Iconable
{
	/**
	 * The icon object
	 * 
	 * @var Pie_Easy_Icon
	 */
	private $icon;

	/**
	 * Set/Return the icon object
	 *
	 * @param  $icon
	 * @return Pie_Easy_Icon
	 */
	public function icon( Pie_Easy_Icon $icon = null )
	{
		if ( $icon ) {
			$this->icon = $icon;
		} else {
			return $this->icon;
		}
	}

	/**
	 * Set toolbar toggle
	 *
	 * @param boolean $toggle
	 */
	public function set_toolbar( $toggle )
	{
		$this->set_directive( 'toolbar', $toggle );
	}
}

?>
