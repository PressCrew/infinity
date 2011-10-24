<?php
/**
 * PIE API: base asset abstract class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/exportable', 'base/recursable', 'collections', 'utils/files' );

/**
 * Make assets for components easy
 *
 * @package PIE
 * @subpackage ui
 * @property-read string $name
 */
abstract class Pie_Easy_Asset extends Pie_Easy_Base
	implements Pie_Easy_Exportable, Pie_Easy_Recursable
{
	/**
	 * Stack of sections
	 * 
	 * @var Pie_Easy_Map
	 */
	private $sections;

	/**
	 * The PCRE pattern at which to split the sections
	 * 
	 * @var string
	 */
	private $sections_pattern =
		'/(\/[*]{2}\h*\v(?:\h+[*]\V*\v)*\h+[*]\h+@section\h+([a-z0-9]+)\h*\v(?:\h+[*]\V*\v)*\h+[*]+\/)/';

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
	 * The strings
	 *
	 * @var Pie_Easy_Stack
	 */
	private $strings;

	/**
	 * An option exporter instance
	 *
	 * @var Pie_Easy_Export
	 */
	private $exporter;

	/**
	 * Callback to exec immediately before exporting
	 *
	 * @var string|array
	 */
	protected $pre_export_callback;
	
	/**
	 * Files that have already been imported
	 *
	 * @var Pie_Easy_Stack
	 */
	private static $files_imported;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// init maps and stacks
		$this->sections = new Pie_Easy_Map();
		$this->deps = new Pie_Easy_Stack();
		$this->files = new Pie_Easy_Stack();
		$this->strings = new Pie_Easy_Stack();

		if ( !self::$files_imported instanceof Pie_Easy_Stack ) {
			self::$files_imported = new Pie_Easy_Stack();
		}
	}

	/**
	 * @return Pie_Easy_Asset
	 */
	public function __call( $name, $arguments )
	{
		// check if there is a section with the name of the method
		if ( $this->sections->contains( $name ) ) {
			// found one, return that asset instance
			return $this->sections->item_at( $name );
		} else {
			throw new Exception( sprintf( 'No asset section with the name "%s" exists.', $name ) );
		}
	}

	/**
	 * Add a section to the asset
	 * 
	 * @param string $name
	 * @return Pie_Easy_Asset 
	 */
	public function add_section( $name )
	{
		// get class of this object
		$class = get_class( $this );
		
		// new "sub asset"
		$asset = new $class();

		// add exporter if applicable
		if ( $this->exporter instanceof Pie_Easy_Export ) {
			// yep, use section name
			$exporter = $this->exporter->child( $name );
			$asset->exporter( $exporter );
		}

		// and new "sub asset" of same class
		$this->sections->add( $name, $asset );

		return $this;
	}

	/**
	 * Returns true if this asset has any sections
	 *
	 * @return boolean
	 */
	public function has_sections()
	{
		return ( $this->sections->count() );
	}

	/**
	 * Returns one of this asset's sections
	 *
	 * @return Pie_Easy_Asset
	 */
	public function get_section( $name )
	{
		return $this->sections->item_at( $name );
	}

	/**
	 * Returns all of this asset's sections as an array
	 *
	 * @return array
	 */
	public function get_sections()
	{
		return $this->sections->to_array();
	}

	/**
	 */
	public function get_children()
	{
		return $this->get_sections();
	}

	/**
	 * Add a dependancy
	 *
	 * @param string $dep_handle Handle of the dependancy to add
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
	 * Push all of this asset's deps onto the given stack
	 *
	 * @return true
	 */
	public function push_deps( Pie_Easy_Stack $stack )
	{
		foreach ( $this->deps as $dep ) {
			$stack->push( $dep );
		}

		return true;
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
	 * Push an arbitrary string on the stack
	 *
	 * @param string $string
	 */
	public function add_string( $string )
	{
		$this->strings->push( $string );
	}

	/**
	 * Return true if this asset has strings
	 *
	 * @return boolean
	 */
	public function has_strings()
	{
		return ( $this->strings->count() );
	}

	/**
	 * Return all of this asset's strings as an array
	 *
	 * @return array
	 */
	public function get_strings()
	{
		return $this->strings->to_array();
	}

	/**
	 * Inject static code/markup
	 *
	 * @return string
	 */
	public function export()
	{
		// exec the pre export callback if applicable
		if ( ( $this->pre_export_callback ) && is_callable( $this->pre_export_callback ) ) {
			call_user_func( $this->pre_export_callback );
		}
		
		// the code that will be returned
		$code = null;

		// have any files?
		if ( $this->has_files() ) {

			// loop through all files
			foreach ( $this->get_files() as $file ) {

				// resolve file path
				if ( path_is_absolute( $file ) ) {
					$filename = $file;
				} else {
					$filename = Pie_Easy_Scheme::instance()->locate_file( $file );
				}

				// only import each file once!
				if ( self::$files_imported->contains( $filename ) ) {
					// already imported that one
					continue;
				} else {
					// push it on to imported stack
					self::$files_imported->push( $filename );
				}
				
				// inject helpful comment ;)
				//$code .= '/*+++ import source: ' . $filename . ' */' . PHP_EOL;

				// make sure file actually exists
				if ( Pie_Easy_Files::cache($filename)->is_readable() ) {

					// get entire contents of file
					$data = $this->get_file_contents( $filename ) . PHP_EOL;

					// any sections to handle?
					if ( $this->sections->count() ) {

						// we have sections! need to split it up
						$data_parts = preg_split( $this->sections_pattern, $data, null, PREG_SPLIT_DELIM_CAPTURE );

						// loop data parts
						while( count( $data_parts ) > 1 ) {
							// pop section content off end
							$section_content = trim( array_pop( $data_parts ) );
							// section name is next item "up"
							$section_name = trim( array_pop( $data_parts ) );
							// section comment is next item "up"
							$section_comment = trim( array_pop( $data_parts ) );
							// add string to section if it exists
							if ( $this->sections->contains( $section_name ) ) {
								// add section comment and content as string
								$this->sections->item_at( $section_name )->add_string( $section_comment );
								$this->sections->item_at( $section_name )->add_string( $section_content );
							}
						}

						// data in the zero key is THIS asset's code
						$code .= array_key_exists( 0, $data_parts ) ? $data_parts[0] : null;

					} else {
						// no sections, just append data as is
						$code .= $data;
					}

					// success
					//$code .= '/*--- import complete! */' . PHP_EOL . PHP_EOL;

				} else {
					//$code .= '/*!!! import failed! */' . PHP_EOL . PHP_EOL;
				}
			}
		}

		// handle strings
		if ( $this->has_strings() ) {
			//$code .= '/*--- importing strings */' . PHP_EOL;
			$code .=
				implode( PHP_EOL, $this->strings->to_array() ) .
				str_repeat( PHP_EOL, 2 );
			//$code .= '/*!!! importing strings complete */' . PHP_EOL;
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
	 * Get/Set current exporter
	 * 
	 * @param Pie_Easy_Export $exporter
	 * @return Pie_Easy_Export
	 */
	public function exporter( Pie_Easy_Export $exporter = null )
	{
		// setting?
		if ( $exporter ) {
			// already set?
			if ( $this->exporter instanceof Pie_Easy_Export ) {
				throw new Exception( 'Cannot set exporter, already set' );
			} else {
				// ok, set it
				$this->exporter = $exporter;
				// push myself onto export stack
				$this->exporter->push( $this );
			}
		}

		// return exporter instance
		if ( $this->exporter instanceof Pie_Easy_Export ) {
			return $this->exporter;
		} else {
			throw new Exception( 'No exporter has been set' );
		}
	}

	/**
	 * Callback to exec immediately before exporting
	 *
	 * @param string|array $callback
	 * @return Pie_Easy_Asset
	 */
	public function on_export( $callback )
	{
		if ( is_callable( $callback ) ) {
			$this->pre_export_callback = $callback;
		} else {
			throw new Exception( 'The callback provided is not callable' );
		}

		return $this;
	}
}

?>
