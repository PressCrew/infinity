<?php
/**
 * PIE API: base asset abstract class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load( 'collections', 'utils/files' );

/**
 * Make assets for components easy
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Asset
{
	/**
	 * Script dependancies
	 *
	 * @var Pie_Easy_Stack
	 */
	private $deps;

	/**
	 * The files
	 *
	 * @var Pie_Easy_Map
	 */
	private $files;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// init stacks and maps
		$this->deps = new Pie_Easy_Stack();
		$this->files = new Pie_Easy_Map();
	}

	/**
	 * Add a dependancy
	 */
	public function add_dep( $dep_handle )
	{
		// just in case
		$dep_handle = trim( $dep_handle );

		if ( strlen( $dep_handle ) && !$this->deps->contains( $dep_handle ) ) {
			$this->deps->push( $dep_handle );
		}
	}

	/**
	 * Return true if at least one dep has been added
	 *
	 * @return boolen
	 */
	public function has_deps()
	{
		return ( $this->deps->count() );
	}

	/**
	 * Return array of dep handles
	 *
	 * @return array
	 */
	public function get_deps()
	{
		return $this->deps->to_array();
	}

	/**
	 * Add a file
	 *
	 * @param string $file
	 * @param array $deps
	 */
	public function add_file( $handle, $file, $deps = null )
	{
		// add file to map
		$this->files->add( $handle, $file );

		// add deps to stack
		if ( is_array($deps) && count($deps) ) {
			foreach( $deps as $dep ) {
				$this->add_dep($dep);
			}
		}
	}

	/**
	 * Return true if at least one file has been added
	 *
	 * @return boolen
	 */
	public function has_files()
	{
		return ( $this->files->count() );
	}

	/**
	 * Return all files as an array
	 *
	 * @return array
	 */
	public function get_files()
	{
		return $this->files->to_array();
	}

	/**
	 * Generate dynamic code/markup
	 *
	 * @return string
	 */
	abstract public function export();
}

?>
