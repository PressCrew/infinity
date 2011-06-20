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
		// load it from alternate location
		$class_name = $this->policy()->load_ext( $ext );

		// get one?
		if ( !$class_name ) {
			// nope, load core option
			Pie_Easy_Loader::load_ext( array('options', $ext) );
			// determine class name
			$class_name = Pie_Easy_Files::file_to_class( $ext, 'Pie_Easy_Exts_Option' );
		}

		// update ext map
		if ( $this->ext( $class_name, $ext ) ) {
			// return it
			return new $class_name( $theme, $name, $title, $desc, $section );
		}
	}
}

?>
