<?php
/**
 * PIE API: extensions option factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage exts
 * @since 1.0
 */

/**
 * Make creating extended option objects easy
 *
 * @package PIE
 * @subpackage exts
 */
class Pie_Easy_Exts_Option_Factory
{
	/**
	 * Singleton constructor
	 * 
	 * @ignore
	 */
	private function __construct() {}

	/**
	 * @ignore
	 * @return Pie_Easy_Options_Option
	 */
	public function create( $ext, Pie_Easy_Options_Option_Conf $conf, $theme, $name, $title, $desc, $section )
	{
		// load it
		Pie_Easy_Loader::load_ext( array('options', $ext) );

		// determine class name
		$class = 'Pie_Easy_Exts_Option_' . ucfirst( $ext );
		
		// make sure it exists
		if ( class_exists( $class ) ) {
			// return it
			return new $class( $conf, $theme, $name, $title, $desc, $section );
		} else {
			// doh!
			throw new Exception( sprintf( 'The option class "%s" does not exist', $class ) );
		}
	}
}

?>
