<?php
/**
 * PIE API: options factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/factory' );

/**
 * Make creating option objects easy
 *
 * @package PIE
 * @subpackage options
 */
class Pie_Easy_Options_Factory extends Pie_Easy_Factory
{
	/**
	 * @ignore
	 * @return Pie_Easy_Options_Option
	 */
	public function create( $ext, $theme, $name, $title = null, $desc = null, $section = null )
	{
		// load it from alternate location?
		if ( !$this->policy()->load_ext( $ext) ) {
			// nope, load core option
			Pie_Easy_Loader::load_ext( array('options', $ext) );
		}

		// determine class name
		$class = 'Pie_Easy_Exts_Option_' . ucfirst( $ext );
		
		// make sure it exists
		if ( class_exists( $class ) ) {
			// return it
			return new $class( $theme, $name, $title, $desc, $section );
		} else {
			// doh!
			throw new Exception( sprintf( 'The option class "%s" does not exist', $class ) );
		}
	}
}

?>
