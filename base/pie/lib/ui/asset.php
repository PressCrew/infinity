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

Pie_Easy_Loader::load( 'base/exportable', 'base/recursable' );

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
	 * @var Pie_Easy_Component 
	 */
	private $component;

	/**
	 * Stack of sections
	 * 
	 * @var Pie_Easy_Map
	 */
	private $sections;

	/**
	 * Script dependancies
	 *
	 * @var Pie_Easy_Stack
	 */
	private $deps;

	/**
	 * The files to enqueue
	 *
	 * @todo this functionality does not exist yet
	 * @var Pie_Easy_Stack
	 */
	private $files;

	/**
	 * The files to export
	 *
	 * @var Pie_Easy_Stack
	 */
	private $files_export;

	/**
	 * Export callbacks
	 *
	 * @var Pie_Easy_Stack
	 */
	private $callbacks;

	/**
	 * The strings
	 *
	 * @var Pie_Easy_Stack
	 */
	private $strings;

	/**
	 * Files that have already been imported
	 *
	 * @var Pie_Easy_Stack
	 */
	private static $files_imported;

	/**
	 * Constructor
	 */
	public function __construct( Pie_Easy_Component $component = null )
	{
		// get a component?
		if ( $component ) {
			$this->component = $component;
		}

		// init maps and stacks
		$this->sections = new Pie_Easy_Map();
		$this->deps = new Pie_Easy_Stack();
		$this->files = new Pie_Easy_Map();
		$this->files_export = new Pie_Easy_Map();
		$this->callbacks = new Pie_Easy_Map();
		$this->strings = new Pie_Easy_Stack();

		if ( !self::$files_imported instanceof Pie_Easy_Stack ) {
			self::$files_imported = new Pie_Easy_Stack();
		}
	}

	/**
	 * Return the component which "owns" this asset instance
	 *
	 * @return Pie_Easy_Component
	 */
	final protected function component()
	{
		if ( $this->component instanceof Pie_Easy_Component ) {
			return $this->component;
		} else {
			throw new Exception( 'No component is set' );
		}
	}

	/**
	 * Return the section for the given name
	 *
	 * @param string $name
	 * @return Pie_Easy_Asset
	 */
	final public function section( $name )
	{
		return $this->get_section( $name );
	}

	/**
	 * Enqueue an asset which has already been registered (as a dependancy)
	 * 
	 * @return Pie_Easy_Asset
	 */
	abstract public function enqueue( $handle );

	/**
	 * Add a file or callback from which to populate the dynamic asset cache
	 *
	 * @param string $handle
	 * @param string|array $file_or_callback
	 * @return Pie_Easy_Asset
	 */
	final public function cache( $handle, $file_or_callback )
	{
		// does string have an extension?
		if ( is_string( $file_or_callback) && strpos( $file_or_callback, '.' ) ) {
			// string contains a dot, assume its a file
			$this->add_file( $handle, $file_or_callback, true );
		} else {
			// nope... determine if it could be a callback
			if ( method_exists( $this->component(), $file_or_callback ) ) {
				// its a method of the component
				$this->add_callback(
					$handle,
					array( $this->component(), $file_or_callback )
				);
			} elseif ( is_callable( $file_or_callback, true ) ) {
				// it *might* be a valid callback
				$this->add_callback( $handle, $file_or_callback );
			}
		}

		// maintain chain
		return $this;
	}

	/**
	 * Add a section to the asset
	 * 
	 * @param string $name
	 * @return Pie_Easy_Asset 
	 */
	final public function add_section( $name )
	{
		// get class of this object
		$class = get_class( $this );
		
		// new "sub asset"
		$asset = new $class( $this->component );

		// add new "sub asset" of same class
		$this->sections->add( $name, $asset );

		return $this;
	}

	/**
	 * Returns true if this asset has any sections
	 *
	 * @return boolean
	 */
	final public function has_sections()
	{
		return ( $this->sections->count() );
	}

	/**
	 * Returns one of this asset's sections if applicable
	 *
	 * @return Pie_Easy_Asset
	 */
	final public function get_section( $name )
	{
		if ( is_string( $name ) ) {
			return $this->sections->item_at( $name );
		} else {
			return $this;
		}
	}

	/**
	 * Returns all of this asset's sections as an array
	 *
	 * @return array
	 */
	final public function get_sections()
	{
		return $this->sections->to_array();
	}

	/**
	 */
	final public function get_children()
	{
		return $this->get_sections();
	}

	/**
	 * Add a dependancy
	 *
	 * @param string $dep_handle Handle of the dependancy to add
	 * @return Pie_Easy_Asset
	 */
	final public function add_dep( $dep_handle )
	{
		// just in case
		$dep_handle = trim( $dep_handle );

		if ( strlen( $dep_handle ) && !$this->deps->contains( $dep_handle ) ) {
			$this->deps->push( $dep_handle );
		}

		return $this;
	}

	/**
	 * Return true if at least one dep has been added
	 *
	 * @return boolen
	 */
	final public function has_deps()
	{
		return ( $this->deps->count() );
	}

	/**
	 * Return array of dep handles
	 *
	 * @return array
	 */
	final public function get_deps()
	{
		return $this->deps->to_array();
	}

	/**
	 * Push all of this asset's deps onto the given stack
	 *
	 * @return Pie_Easy_Asset
	 */
	final public function push_deps( Pie_Easy_Stack $stack )
	{
		foreach ( $this->deps as $dep ) {
			if ( !$stack->contains( $dep ) ) {
				$stack->push( $dep );
			}
		}

		return $this;
	}

	/**
	 * Add a file
	 *
	 * @param string $handle
	 * @param string $file
	 * @param boolean $cache
	 * @return Pie_Easy_Asset
	 */
	final public function add_file( $handle, $file, $cache = false )
	{
		// push file onto applicable stack
		if ( true === $cache ) {
			$this->files_export->add( $handle, $file );
		} else {
			$this->files->add( $handle, $file );
		}

		// maintain chain
		return $this;
	}

	/**
	 * Return true if at least one file has been added
	 *
	 * @return boolen
	 */
	final public function has_files()
	{
		return ( $this->files->count() );
	}

	/**
	 * Return all files as an array
	 *
	 * @return array
	 */
	final public function get_files()
	{
		return $this->files->to_array();
	}

	/**
	 * Add a callback to execute just before exporting
	 *
	 * @param string $handle
	 * @param string|array $callback
	 * @return Pie_Easy_Asset
	 */
	final public function add_callback( $handle, $callback )
	{
		if ( is_callable( $callback ) ) {
			$this->callbacks->add( $handle, $callback );
		} else {
			throw new Exception( sprintf( 'The callback for handle "%s" is not callable', $handle ) );
		}

		return $this;
	}

	/**
	 * Returns true if at least one callback has been added
	 * 
	 * @return boolean
	 */
	final public function has_callbacks()
	{
		return ( $this->callbacks->count() );
	}

	/**
	 * Push an arbitrary string on the stack
	 *
	 * @param string $string
	 * @return Pie_Easy_Asset
	 */
	final public function add_string( $string )
	{
		$this->strings->push( $string );

		return $this;
	}

	/**
	 * Return true if this asset has strings
	 *
	 * @return boolean
	 */
	final public function has_strings()
	{
		return ( $this->strings->count() );
	}

	/**
	 * Return all of this asset's strings as an array
	 *
	 * @return array
	 */
	final public function get_strings()
	{
		return $this->strings->to_array();
	}

	/**
	 * Inject static code/markup
	 *
	 * @todo get rid of file splitting!
	 * @return string
	 */
	public function export()
	{
		// the code that will be returned
		$code = null;

		// handle callbacks
		if ( $this->has_callbacks() ) {
			// loop em
			foreach ( $this->callbacks as $callback ) {
				// execute callback with myself as only argument
				call_user_func( $callback, $this );
			}
		}

		// have any files?
		if ( $this->files_export->count() ) {

			// loop through all files
			foreach ( $this->files_export as $file ) {

				// resolve file path
				if ( path_is_absolute( $file ) ) {
					// its absolute already, which is good
					$filename = $file;
				} else {
					// relative path, need to locate it
					$filename = $this->component()->locate_file( $file );
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
					$code .= $this->get_file_contents( $filename ) . PHP_EOL;

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
}

?>
