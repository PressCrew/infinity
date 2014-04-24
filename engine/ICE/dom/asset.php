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
	 * The files to import.
	 *
	 * @var array
	 */
	private $files = array();

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
	 * Files that have already been imported.
	 *
	 * @var array
	 */
	private static $files_imported = array();

	/**
	 * Constructor
	 */
	public function __construct( ICE_Component $component = null )
	{
		// get a component?
		if ( $component ) {
			$this->component = $component;
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
	 * Add a file to import.
	 *
	 * @param string $handle
	 * @param string $file
	 * @return ICE_Asset
	 */
	final public function add_file( $handle, $file  )
	{
		// push file onto stack
		$this->files[ $handle ] = $file;

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
	 * Render static code/markup
	 *
	 * @return string
	 */
	public function render()
	{
		// render callbacks
		foreach ( $this->callbacks as $handle => $callback ) {
			// execute callback with myself as only argument
			call_user_func( $callback, $this );
		}

		// render files
		foreach ( $this->files as $handle => $file ) {

			// resolve file path
			if ( ICE_Files::path_is_absolute( $file ) ) {
				// its absolute already, which is good
				$filename = $file;
			} else {
				// relative path, need to locate it
				$filename = $this->component()->locate_file( $file );
			}

			// only import each file once!
			if ( in_array( $filename, self::$files_imported ) )  {
				// already imported that one
				continue;
			} else {
				// push it on to imported stack
				self::$files_imported[] = $filename;
			}

			// inject helpful comment ;)
			//echo .= '/*+++ import source: ' . $filename . ' */' . PHP_EOL;

			// make sure file actually exists
			if ( is_readable( $filename ) ) {

				// get entire contents of file
				echo $this->get_file_contents( $filename ) . PHP_EOL;

				// success
				//echo '/*--- import complete! */' . PHP_EOL . PHP_EOL;

			} else {
				//echo '/*!!! import failed! */' . PHP_EOL . PHP_EOL;
			}
		}

		// render strings
		foreach ( $this->strings as $handle => $string ) {
			// print it
			echo $string, PHP_EOL, PHP_EOL;
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
