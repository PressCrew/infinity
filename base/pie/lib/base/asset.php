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
	 * @var Pie_Easy_Stack
	 */
	private $files;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// init stacks
		$this->deps = new Pie_Easy_Stack();
		$this->files = new Pie_Easy_Stack();
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
	public function add_file( $file, $deps = null )
	{
		// push file onto stack
		$this->files->push( $file );

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
	 * Inject static code/markup
	 *
	 * @return string
	 */
	public function import()
	{
		// the code that will be returned
		$code = null;

		// have any files?
		if ( $this->has_files() ) {

			// loop through all files
			foreach ( $this->get_files() as $file ) {

				// resolve file path
				if ( $file{0} == DIRECTORY_SEPARATOR ) {
					$filename = $file;
				} else {
					$filename = Pie_Easy_Scheme::instance()->locate_file( $file );
				}

				// inject helpful comment ;)
				$code .= '/*+++ import source: /../' . Pie_Easy_Files::theme_file_to_rel( $filename ) . ' */' . PHP_EOL;

				// make sure it actually exists
				if ( Pie_Easy_Files::cache($filename)->is_readable() ) {
					$code .= $this->get_file_contents( $filename ) . PHP_EOL;
					$code .= '/*--- import complete! */' . PHP_EOL . PHP_EOL;
				} else {
					$code .= '/*!!! import failed! */' . PHP_EOL . PHP_EOL;
				}
			}
		}

		// all done
		return $code;
	}

	/**
	 * Return contents of a file that is being imported
	 *
	 * @param string $filename
	 * @return string
	 */
	protected function get_file_contents( $filename )
	{
		return file_get_contents( $filename );
	}

	/**
	 * Generate dynamic code/markup
	 *
	 * @return string
	 */
	abstract public function export();
}

?>
