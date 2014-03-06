<?php
/**
 * ICE API: init extensions class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage init
 * @since 1.2
 */

/**
 * Make tracking custom extensions easy.
 *
 * @package ICE
 * @subpackage init
 */
class ICE_Extensions
{
	const KEY_EXTENDS = 'extends';
	const KEY_PATH = 'path';
	const KEY_CLASS = 'class';
	const KEY_OPTS = 'options';
	const KEY_TPL = 'template';

	/**
	 * Default template file name.
	 */
	const DEFAULT_TPL = 'template.php';

	/**
	 * Path prefix to use if path setting is relative.
	 * 
	 * @var string 
	 */
	private $path_prefix;

	/**
	 * The default extension settings.
	 *
	 * @var array
	 */
	private $defaults = array(
		self::KEY_PATH => '',
		self::KEY_CLASS => '',
		self::KEY_EXTENDS => false,
		self::KEY_TPL => false,
		self::KEY_OPTS => array()
	);

	/**
	 * Array of registered extensions.
	 *
	 * @var array
	 */
	private $extensions = array();

	/**
	 * Array of loaded classes and their extension type.
	 *
	 * @var array
	 */
	private $loaded = array();

	/**
	 * Constructor.
	 *
	 * @param ICE_Policy $policy
	 */
	public function __construct( ICE_Policy $policy )
	{
		// determine path prefix
		$this->path_prefix = ICE_EXT_PATH . '/' . $policy->get_handle( true );
	}

	/**
	 * Register an extension.
	 *
	 * @param string $ext
	 * @param array $settings
	 * @return boolean
	 */
	public function register( $ext, $settings )
	{
		// is extension registered?
		if ( false === isset( $this->extensions[ $ext ] ) ) {
			// assign defaults
			$this->extensions[ $ext ] = $this->defaults;
			// maybe override with passed settings
			foreach( $settings as $key => $value ) {
				// is the key valid?
				if ( isset( $this->extensions[ $ext ][ $key ] ) ) {
					// yep, override it
					$this->extensions[ $ext ][ $key ] = $value;
				}
			}
		}

		// all done
		return true;
	}

	/**
	 * Load a file in context so it can make calls to register() in scope.
	 *
	 * @param string $filename
	 */
	public function register_file( $filename )
	{
		require_once $filename;
	}

	/**
	 * Return all settings for given extension.
	 *
	 * @param string $ext
	 * @return array
	 */
	public function get_settings( $ext )
	{
		if ( isset( $this->extensions[ $ext ] ) ) {
			return $this->extensions[ $ext ];
		}

		return array();
	}

	/**
	 * Loads an extension and returns the name of the extension class.
	 *
	 * @param string $ext
	 * @return string
	 * @throws RuntimeException
	 * @throws UnexpectedValueException
	 */
	public function load( $ext )
	{
		// is the extension registered?
		if ( true === isset( $this->extensions[ $ext ] ) ) {
			// yes, get class name from settings
			$class_name = $this->extensions[ $ext ][ self::KEY_CLASS ];
			// does that class exist?
			if ( false === class_exists( $class_name, false ) ) {
				// nope, get extends from settings
				$extends = $this->extensions[ $ext ][ self::KEY_EXTENDS ];
				// does it extend anything?
				if ( false === empty( $extends ) ) {
					// yes, recursive load
					$this->load( $extends );
				}
				// load the file
				$this->load_file( $ext, 'class.php' );
				// did the file we just loaded define the class we were expecting?
				if ( true === class_exists( $class_name ) ) {
					// yep, add class => ext to loaded array
					$this->loaded[ $class_name ] = $ext;
				} else {
					// nope, class STILL not defined, fatal
					throw new RuntimeException(
						sprintf( 'The class "%s" does not exist', $class_name ) );
				}
			}
			// extension loaded, return class name
			return $class_name;
		} else {
			// extension not registered, fatal
			throw new UnexpectedValueException(
				sprintf( 'Failed to load the "%s" extension: Not registered', $ext ) );
		}
	}

	/**
	 * Returns true or ext name for the given class if it has been loaded.
	 *
	 * @param string $class_name Class name to check.
	 * @param boolean $return_string Set to true to return ext name (string) instead of (boolean)
	 * @return boolean|string
	 */
	public function loaded( $class_name, $return_string = false )
	{
		// is class in loaded array?
		if ( true === isset( $this->loaded[ $class_name ] ) ) {
			// yep return array or true
			return ( true === $return_string ) ? $this->loaded[ $class_name ] : true;
		}

		// class not loaded
		return false;
	}

	/**
	 * Load a file for the given extension.
	 *
	 * @param string $ext
	 * @param string $file
	 * @return boolean
	 */
	private function load_file( $ext, $file )
	{
		// reference path setting
		$path =& $this->extensions[ $ext ][ self::KEY_PATH ];

		// is path empty?
		if ( empty( $path ) ) {
			// yes, use magic
			$path = $this->path_prefix . '/' . $ext;
		}

		// try to load file
		require_once $path . '/' . $file;

		// all done
		return true;
	}

	/**
	 * Resolve an extension file path (with default hint).
	 *
	 * This differs from locate_file() in that it resolves default paths which have been
	 * set via register(). These can be absolute paths that live *outside* of the extension
	 * path.
	 *
	 * @param string $ext The extension.
	 * @param string $key The file setting key.
	 * @param string $default Default file name hint. Used to short circuit absolute path check (performance).
	 * @return string
	 */
	private function resolve_file( $ext, $key, $default )
	{
		// init file
		$file = null;

		// is file key set for extension?
		if ( false === empty( $this->extensions[ $ext ][ $key ] ) ) {
			// get the file
			$file = $this->extensions[ $ext ][ $key ];
		}

		// empty string by default
		$path_resolved = '';

		// is file set?
		if ( null !== $file ) {
			// get the path
			$path = $this->extensions[ $ext ][ self::KEY_PATH ];
			// is it a relative path?
			if (
				// default is always relative
				$default === $file ||
				// check if NOT absolute
				false === ICE_Files::path_is_absolute( $file )
			) {
				// its relative, prepend it with the path
				$path_resolved = $path . '/' . $file;
			} else {
				// hopefully an absolute path, use as is
				$path_resolved = $file;
			}
		} else {
			// call parent file resolver
			$path_resolved = $this->resolve_parent_file( $ext, $key, $default );
		}

		// return resolved path
		return $path_resolved;
	}

	/**
	 * Resolve an extension's *parent* file path (with default hint).
	 *
	 * @param string $ext The extension.
	 * @param string $key The file setting key.
	 * @param string $default Default file name hint. Used to short circuit absolute path check (performance).
	 * @return string
	 */
	private function resolve_parent_file( $ext, $key, $default )
	{
		// does it extend anything?
		if ( false === empty( $this->extensions[ $ext ][ self::KEY_EXTENDS ] ) ) {
			// get extends
			$extends = $this->extensions[ $ext ][ self::KEY_EXTENDS ];
			// call resursively to resolve path
			return $this->resolve_file( $extends, $key, $default );
		}

		// doesn't extend anything
		return '';
	}

	/**
	 * Locate a file for the given extension *relative* to the "path" setting.
	 *
	 * @param string $ext The extension to check.
	 * @param string $filename Relative path to file.
	 * @return string|false
	 */
	public function locate_file( $ext, $filename )
	{
		// is path set for given extension?
		if ( isset( $this->extensions[ $ext ][ self::KEY_PATH ] ) ) {
			// yep, complete the path
			$abs_path = $this->extensions[ $ext ][ self::KEY_PATH ] . '/' . $filename;
			// does file exist?
			if ( true === file_exists( $abs_path ) ) {
				// path exists and is readable, return it
				return $abs_path;
			} else {
				// doesn't exist, recurse up stack
				return $this->locate_parent_file( $ext, $filename );
			}
		}

		// file not found
		return false;
	}

	/**
	 * Locate a file for the *parent* of the given extension *relative* to the "path" setting.
	 *
	 * @param string $ext The extension to check.
	 * @param string $filename Relative path to file.
	 * @return string|false
	 */
	public function locate_parent_file( $ext, $filename )
	{
		// does it extend anything?
		if ( false === empty( $this->extensions[ $ext ][ self::KEY_EXTENDS ] ) ) {
			// get extends
			$extends = $this->extensions[ $ext ][ self::KEY_EXTENDS ];
			// call locate file
			return $this->locate_file( $extends, $filename );
		}

		// doesn't extend anything
		return false;
	}

	/**
	 * Get template path for extension type.
	 *
	 * @param string $ext
	 * @return string
	 */
	public function get_template_path( $ext )
	{
		return $this->resolve_file( $ext, self::KEY_TPL, self::DEFAULT_TPL );
	}
}
