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

Pie_Easy_Loader::load( 'base/componentable' );

/**
 * Make creating concrete components easy
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Factory extends Pie_Easy_Componentable
{
	/**
	 * Map of created classes and their extension type
	 * 
	 * @var Pie_Easy_Map
	 */
	private $ext_map;

	/**
	 * Set or get the ext that triggered a class to be loaded
	 *
	 * @param object|string $class_name
	 * @param string $ext
	 * @return string|false
	 */
	final public function ext( $class, $ext = null )
	{
		// handle map initialization
		if ( !$this->ext_map instanceof Pie_Easy_Map ) {
			$this->ext_map = new Pie_Easy_Map();
		}

		// class name
		$class_name = ( is_object( $class ) ) ? get_class( $class ) : $class;

		// set or get
		if ( $ext ) {
			// class must exist
			if ( class_exists( $class_name ) ) {
				// update map
				$this->ext_map->add( $class_name, $ext );
				return true;
			} else {
				throw new Exception( sprintf( 'The class "%s" does not exist', $class_name ) );
			}
		} else {
			return $this->ext_map->item_at( $class_name );
		}

	}

	/**
	 * Return an instance of a component
	 *
	 * @todo sending section through is a temp hack
	 * @return Pie_Easy_Component
	 */
	abstract public function create( $ext, $theme, $name, $title = null, $desc = null, $section = null );
	
}

?>
