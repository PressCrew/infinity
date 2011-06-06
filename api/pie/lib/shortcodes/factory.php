<?php
/**
 * PIE API: shortcodes factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage shortcodes
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/factory' );

/**
 * Make creating shortcode objects easy
 *
 * @package PIE
 * @subpackage shortcodes
 */
class Pie_Easy_Shortcodes_Factory extends Pie_Easy_Factory
{
	/**
	 * @todo sending section through is a temp hack
	 * @ignore
	 * @return Pie_Easy_Shortcodes_Shortcode
	 */
	public function create( $ext, $theme, $name, $title = null, $desc = null, $section = null )
	{
		// load it from alternate location?
		if ( !$this->policy()->load_ext( $ext ) ) {
			// nope, load core shortcode
			Pie_Easy_Loader::load_ext( array('shortcodes', $ext) );
		}

		// determine class name
		$class = 'Pie_Easy_Exts_Shortcode_' . ucfirst( $ext );
		
		// make sure it exists
		if ( class_exists( $class ) ) {
			// return it
			return new $class( $theme, $name, $title, $desc );
		} else {
			// doh!
			throw new Exception( sprintf( 'The shortcode class "%s" does not exist', $class ) );
		}
	}
}

?>
