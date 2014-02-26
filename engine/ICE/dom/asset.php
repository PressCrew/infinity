<?php
/**
 * ICE API: base asset abstract class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage dom
 * @since 1.0
 */

/**
 * Make assets for components easy
 *
 * @package ICE
 * @subpackage dom
 */
abstract class ICE_Asset extends ICE_Base
{
	/**
	 * @var ICE_Component 
	 */
	private $component;

	/**
	 * Stack of sections
	 * 
	 * @var ICE_Map
	 */
	private $sections = array();

	/**
	 * Script dependancies
	 *
	 * @var array
	 */
	private $deps = array();

	/**
	 * The files to enqueue
	 *
	 * @var array
	 */
	private $files = array();

	/**
	 * The files to export
	 *
	 * @var array
	 */
	private $files_export = array();

	/**
	 * Export callbacks
	 *
	 * @var array
	 */
	private $callbacks = array();

	/**
	 * The strings
	 *
	 * @var array
	 */
	private $strings = array();

	/**
	 * The conditions stack.
	 *
	 * @var array
	 */
	private $conditions = array();

	/**
	 * Files that have already been imported
	 *
	 * @var ICE_Stack
	 */
	private static $files_imported;

	/**
	 * Constructor
	 */
	public function __construct( ICE_Component $component = null )
	{
		// get a component?
		if ( $component ) {
			$this->component = $component;
		}

		// init files imported stack
		if ( !self::$files_imported instanceof ICE_Stack ) {
			self::$files_imported = new ICE_Stack();
		}
	}

	/**
	 * Return the component which "owns" this asset instance
	 *
	 * @return ICE_Component
	 */
	final protected function component()
	{
		if ( $this->component instanceof ICE_Component ) {
			return $this->component;
		} else {
			throw new Exception( 'No component is set' );
		}
	}

	/**
	 * Enqueue an asset which has already been registered.
	 * 
	 * @return ICE_Asset
	 */
	abstract public function enqueue( $handle );

	/**
	 * Add a file or callback from which to inject dynamic asset code.
	 *
	 * @param string $handle
	 * @param string|array $file_or_callback
	 * @return ICE_Asset
	 */
	final public function inject( $handle, $file_or_callback )
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
	 * Add a dependancy for the given handle.
	 *
	 * @param string $handle Handle of the dependant item
	 * @param string $dep_handle Handle of the dependancy to add
	 * @return ICE_Asset
	 */
	final public function add_dep( $handle, $dep_handle )
	{
		// just in case
		$dep_handle = trim( $dep_handle );

		if ( $dep_handle ) {
			$this->deps[ $handle ][] = $dep_handle;
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
		return ( count( $this->deps ) );
	}

	/**
	 * Return array of dep handles
	 *
	 * @return array
	 */
	final public function get_deps()
	{
		// array of deps to return
		$return_deps = array();

		// loop all deps
		foreach ( $this->deps as $handle => $deps ) {
			// check conditions for handle
			if ( true === $this->check_cond( $handle ) ) {
				// loop all handle's deps
				foreach ( $deps as $dep ) {
					// already in stack?
					if ( false === isset( $return_deps[ $dep ] ) ) {
						// nope, push it
						$return_deps[ $dep ] = $dep;
					}
				}
			}
		}

		return $return_deps;
	}

	/**
	 * Enqueue all dependencies of this asset.
	 */
	final public function enqueue_deps()
	{
		// loop all deps
		foreach( $this->get_deps() as $handle ) {
			// call abstract enqueue method
			$this->enqueue( $handle );
		}
	}

	/**
	 * Add a file
	 *
	 * @param string $handle
	 * @param string $file
	 * @param boolean $inject
	 * @return ICE_Asset
	 */
	final public function add_file( $handle, $file, $inject = false )
	{
		// push file onto applicable stack
		if ( true === $inject ) {
			$this->files_export[ $handle ] = $file;
		} else {
			$this->files[ $handle ] = $file;
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
		return ( count( $this->files ) );
	}

	/**
	 * Return all files as an array
	 *
	 * @return array
	 */
	final public function get_files()
	{
		return $this->files;
	}

	/**
	 * Add a callback to execute just before exporting
	 *
	 * @param string $handle
	 * @param string|array $callback
	 * @return ICE_Asset
	 */
	final public function add_callback( $handle, $callback )
	{
		if ( is_callable( $callback ) ) {
			$this->callbacks[ $handle ] = $callback;
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
		return ( count( $this->callbacks ) );
	}

	/**
	 * Push an arbitrary string on the stack
	 *
	 * @param handle $handle
	 * @param string $string
	 * @return ICE_Asset
	 */
	final public function add_string( $handle, $string )
	{
		if ( true === is_string( $string ) ) {
			$this->strings[ $handle ] = $string;
		} else {
			throw new InvalidArgumentException( 'The $string parameter must be a string' );
		}

		return $this;
	}

	/**
	 * Return true if this asset has strings
	 *
	 * @return boolean
	 */
	final public function has_strings()
	{
		return ( count( $this->strings ) );
	}

	/**
	 * Return all of this asset's strings as an array
	 *
	 * @return array
	 */
	final public function get_strings()
	{
		return $this->strings;
	}

	/**
	 * Add a condition for the given handle.
	 *
	 * @param string $handle
	 * @param callable $callback
	 * @param array $param_arr
	 * @return ICE_Asset
	 */
	final public function add_cond( $handle, $callback, $param_arr = array() )
	{
		// callback must be callable
		if ( is_callable( $callback ) ) {
			// make sure we got an array for params
			if ( false === is_array( $param_arr ) ) {
				throw new InvalidArgumentException( sprintf( 'The parameter list for handle "%s" is not an array', $handle ) );
			}
			// set it
			$this->conditions[ $handle ][] = array( $callback, $param_arr );
		} else {
			throw new InvalidArgumentException( sprintf( 'The callback for handle "%s" is not callable', $handle ) );
		}

		return $this;
	}

	/**
	 * Returns true if given handle has one or more conditions set.
	 *
	 * @param string $handle
	 * @return boolean
	 */
	final public function has_cond( $handle )
	{
		return (
			true === isset( $this->conditions[ $handle ] ) &&
			1 <= count( $this->conditions[ $handle ] )
		);
	}

	/**
	 * Return array of conditions for given handle.
	 *
	 * If no conditions exist, and empty array will be returned.
	 *
	 * @param string $handle
	 * @return array
	 */
	final public function get_cond( $handle )
	{
		if ( true === $this->has_cond( $handle ) ) {
			return $this->conditions[ $handle ];
		} else {
			return array();
		}
	}

	/**
	 * Returns true if all conditions set for handle are met.
	 *
	 * @param string $handle
	 * @return boolean
	 */
	final public function check_cond( $handle )
	{
		// are any conditions set?
		if ( true === $this->has_cond( $handle ) ) {
			// yes, grab 'em
			$conditions = $this->conditions[ $handle ];
			// loop every condition and test
			foreach ( $conditions as $condition ) {
				// nobody uses list any more, i know... deal with it
				list( $callback, $param_arr ) = $condition;
				// test it
				if ( false === call_user_func_array( $callback, $param_arr ) ) {
					// test failed, bail out
					return false;
				}
			}
		}

		// no conditions set, or all tests passed, party!
		return true;
	}

	/**
	 * Render static code/markup
	 *
	 * @return string
	 */
	public function render()
	{
		// render callbacks
		foreach ( $this->callbacks as $handle => $callback ) {
			// check conditions
			if ( true === $this->check_cond( $handle ) ) {
				// execute callback with myself as only argument
				call_user_func( $callback, $this );
			}
		}

		// render files
		foreach ( $this->files_export as $handle => $file ) {

			// resolve file path
			if ( ICE_Files::path_is_absolute( $file ) ) {
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
			//echo .= '/*+++ import source: ' . $filename . ' */' . PHP_EOL;

			// make sure file actually exists
			if ( is_readable( $filename ) ) {

				// check conditions
				if ( true === $this->check_cond( $handle ) ) {
					// get entire contents of file
					echo $this->get_file_contents( $filename ) . PHP_EOL;
				}

				// success
				//echo '/*--- import complete! */' . PHP_EOL . PHP_EOL;

			} else {
				//echo '/*!!! import failed! */' . PHP_EOL . PHP_EOL;
			}
		}

		// render strings
		foreach ( $this->strings as $handle => $string ) {
			// check conditions
			if ( true === $this->check_cond( $handle ) ) {
				// print it
				echo $string, PHP_EOL, PHP_EOL;
			}
		}
	}

	/**
	 * Return contents of a file that is being imported
	 *
	 * @param string $filename
	 * @return string
	 */
	protected function get_file_contents( $filename )
	{
		ob_start();
		include $filename;
		return ob_get_clean();
	}
}
