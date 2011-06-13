<?php
/**
 * PIE API: features factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/factory' );

/**
 * Make creating feature objects easy
 *
 * @package PIE
 * @subpackage features
 */
class Pie_Easy_Features_Factory extends Pie_Easy_Factory
{
	/**
	 * @todo sending section through is a temp hack
	 * @ignore
	 * @return Pie_Easy_Features_Feature
	 */
	public function create( $ext, $theme, $name, $title = null, $desc = null, $section = null )
	{
		// load it from alternate location?
		if ( !$this->policy()->load_ext( $ext ) ) {
			// nope, load core feature
			Pie_Easy_Loader::load_ext( array('features', $ext) );
		}

		$ext_parts = explode( '-', $ext );

		foreach ( $ext_parts as &$ext_part ) {
			$ext_part = ucfirst( $ext_part );
		}

		// determine class name
		$class_name = 'Pie_Easy_Exts_Feature_' . implode( '_', $ext_parts );

		// update ext map
		if ( $this->ext( $class_name, $ext ) ) {
			// return it
			return new $class_name( $theme, $name, $title, $desc );
		}
	}
}

?>
