<?php
/**
 * PIE API: base factory class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/componentable', 'utils/files' );

/**
 * Make creating concrete components easy
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Factory extends Pie_Easy_Componentable
{
	/**
	 * Load a component extension
	 *
	 * Override this class to load component class files which exist outside of PIE's path
	 *
	 * @param string $ext Name of the extension
	 * @return string Name of the class which was loaded
	 */
	public function load_ext( $ext )
	{
		// expand extension path
		$ext = sprintf('%s/%s', $this->policy()->get_handle(), $ext );

		// format extension file name
		$ext_file = $ext . DIRECTORY_SEPARATOR . 'class.php';

		// look for scheme files first
		$file_theme =
			Pie_Easy_Scheme::instance()->locate_extension_file( $ext_file );

		// find a theme file?
		if ( $file_theme ) {

			// format class name
			$class_name = Pie_Easy_Files::file_to_class( $ext, PIE_EASY_EXT_PREFIX_API );

			// load the file
			require_once $file_theme;

			// class must exist
			if ( class_exists( $class_name ) ) {
				return $class_name;
			} else {
				throw new Exception( sprintf( 'The class "%s" does not exist in the file %s', $class_name, $file_theme ) );
			}

		} else {
			return Pie_Easy_Loader::load_libext( $ext );
		}
	}

	/**
	 * Return an instance of a component
	 *
	 * @param string $theme
	 * @param string $name
	 * @param Pie_Easy_Map $conf
	 * @return Pie_Easy_Component
	 */
	public function create( $theme, $name, $conf )
	{
		// check if already registered
		if ( $this->policy()->registry()->has( $name ) ) {
			// use that one
			$component = $this->policy()->registry()->get( $name );
		} else {
			// make sure the extension is loaded
			$class_name = $this->load_ext( $conf->type );
			// create new component
			$component = new $class_name( $theme, $name, $conf->type );
			// set the policy
			$component->policy( $this->policy() );
		}

		// configure it
		$component->configure( $conf, $theme );

		// all done
		return $component;
	}

}

?>
