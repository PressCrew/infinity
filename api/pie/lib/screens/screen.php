<?php
/**
 * PIE API: screen class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/component' );

/**
 * Make a display screen easy
 *
 * @package PIE
 * @subpackage screens
 * @property-read string $template Relative path to screen template file
 */
abstract class Pie_Easy_Screens_Screen extends Pie_Easy_Component
{
	/**
	 * Set the template file path
	 *
	 * @param string $path
	 */
	public function set_template( $path )
	{
		$this->set_directive( 'template', $path );
	}
}

?>
