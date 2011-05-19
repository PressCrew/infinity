<?php
/**
 * PIE API: base factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/policeable' );

/**
 * Make creating concrete components easy
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Factory extends Pie_Easy_Policeable
{
	/**
	 * Return an instance of a component
	 *
	 * @todo sending section through is a temp hack
	 * @return Pie_Easy_Component
	 */
	abstract public function create( $ext, $theme, $name, $title = null, $desc = null, $section = null );
	
}

?>
