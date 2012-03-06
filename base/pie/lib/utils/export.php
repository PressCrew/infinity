<?php
/**
 * PIE API: export files helper class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage utils
 * @since 1.0
 */

/**
 * Make exporting dynamic files easy
 *
 * @todo this should extend Pie_Easy_File
 * @package PIE
 * @subpackage utils
 * @uses Pie_Easy_Export_Exception
 * @property-read string $name
 * @property-read string $ext
 * @property-read string $path
 * @property-read string $url
 */
class Pie_Easy_Export extends Pie_Easy_Base
{
	/**
	 * File name delimiter
	 */
	const FILE_NAME_DELIM = '-';

	/**
	 * File extension delimiter
	 */
	const FILE_EXT_DELIM = '.';

	/**
	 * Set to true once dir props have been populated
	 *
	 * @var boolean
	 */
	static private $populated = false;

	/**
	 * Upload directory path for current request
	 *
	 * @var string
	 */
	static private $upload_dir;

	/**
	 * Upload directory URL for current request
	 *
	 * @var string
	 */
	static private $upload_url;

	/**
	 * Export directory path for current request
	 *
	 * @var string
	 */
	static private $export_dir;

	/**
	 * Export URL for current request
	 *
	 * @var string
	 */
	static private $export_url;

	/**
	 * Name of the file (without extension)
	 * 
	 * @var string 
	 */
	private $name;

	/**
	 * The file extension
	 *
	 * @var string
	 */
	private $ext;

	/**
	 * Export file path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Export file URL
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Stack of objects to retrieve export data from
	 *
	 * @var Pie_Easy_Stack
	 */
	private $stack;

	/**
	 * Callback from which to retrieve data
	 *
	 * @var string|array
	 */
	private $callback;

	/**
	 * Map of child instances of self
	 *
	 * @var Pie_Easy_Map
	 */
	private $children;

	/**
	 * Set to true the file has been updated
	 *
	 * @var boolean
	 */
	private $updated = false;

	/**
	 * Constructor
	 *
	 * @param string $name Name of file to manage RELATIVE to export dir
	 */
	public function __construct( $name, $ext, $callback = null )
	{
		// make sure dir info is populated
		$this->populate_dir_props();

		// set primitives
		$this->name = $name;
		$this->ext = $ext;
		$this->callback = $callback;

		// format the complete file name
		$filename = $this->name . self::FILE_EXT_DELIM . $this->ext;

		// determine file path and url
		$this->path = self::$export_dir . '/' . $filename;
		$this->url = self::$export_url . '/' . $filename;
		$this->stack = new Pie_Easy_Stack();
		$this->children = new Pie_Easy_Map();
	}

	/**
	 */
	public function __get( $name )
	{
		switch( $name ) {
			case 'name':
			case 'ext':
			case 'path':
			case 'url':
				return $this->$name;
			default:
				return parent::__get( $name );
		}
	}

	/**
	 */
	public function __isset( $name )
	{
		switch( $name ) {
			case 'name':
			case 'ext':
			case 'path':
			case 'url':
				return isset( $this->$name );
			default:
				return parent::__isset( $name );
		}
	}

	/**
	 * Set static upload directory properties
	 *
	 * @internal
	 * @return boolean
	 */
	private function populate_dir_props()
	{
		// only populate dir info once
		if ( !self::$populated ) {

			// get upload directory details
			$upload_dir = wp_upload_dir();

			// make sure we didn't get an error
			if ( $upload_dir['error'] == false ) {
				// set upload dir path and url
				self::$upload_dir = realpath( $upload_dir['basedir'] );
				self::$upload_url = $upload_dir['baseurl'];
				// determine export path and url
				self::$export_dir = self::$upload_dir . Pie_Easy_Files::path_build( PIE_EASY_EXPORTS_SUBDIR, get_stylesheet() );
				self::$export_url = sprintf( '%s/%s/%s', self::$upload_url, PIE_EASY_EXPORTS_SUBDIR, get_stylesheet() );
				// don't try to set these twice
				self::$populated = true;
				// yay
				return true;
			}

			throw new Pie_Easy_Export_Exception( $upload_dir['error'] );
		}

		return true;
	}

	/**
	 * Get child export instance for name, create if necessary
	 *
	 * @param string $name
	 * @return Pie_Easy_Export
	 */
	public function child( $name )
	{
		// already have this child?
		if ( !$this->children->contains( $name ) ) {
			// nope, create instance of self
			$child = new self( $this->name . self::FILE_NAME_DELIM . $name, $this->ext );
			// push onto children stack
			$this->children->add( $name, $child );
		}
		
		// return the child object
		return $this->children->item_at( $name );
	}

	/**
	 * Push an exportable object onto the stack
	 *
	 * @param Pie_Easy_Exportable $obj
	 * @return Pie_Easy_Stack
	 */
	public function push( Pie_Easy_Exportable $obj )
	{
		// not allowed if callback is set
		if ( $this->callback ) {
			throw new Exception( 'Adding exportable objects not allowed when a callback is set' );
		}

		return $this->stack->push( $obj );
	}

	/**
	 * Write data to the file
	 *
	 * @param string $data
	 * @return boolean
	 */
	public function write( $data = null )
	{
		// make sure the export dir exists
		if ( wp_mkdir_p( self::$export_dir ) ) {
			// can we write to the export dir?
			if ( Pie_Easy_Files::cache(self::$export_dir)->refresh()->is_writable() ) {
				// get file instance
				$file = Pie_Easy_Files::cache($this->path)->refresh();
				// if file already exists, puke if not writeable
				if ( $file->exists() && !$file->is_writable() ) {
					throw new Pie_Easy_Export_Exception(
						'Unable to write to the file: ' . $this->path );
				}
				// try to write it
				$bytes = file_put_contents( $file->getPathname(), $data );
				// any bytes written
				if ( $bytes ) {
					// yep, refresh file
					$file->refresh();
					// return bytes written
					return $bytes;
				}
				return false;
			} else {
				throw new Pie_Easy_Export_Exception(
					'Unable to create the file: ' . $this->path );
			}
		} else {
			throw new Pie_Easy_Export_Exception(
				'Unable to create the directory: ' . self::$export_dir );
		}
	}

	/**
	 * Update the export file
	 *
	 * @return boolean
	 */
	public function update()
	{
		// result is null by default
		$data = null;

		// have a callback
		if ( $this->callback ) {
			// have a callback, make sure its callable
			if ( is_callable( $this->callback ) ) {
				// execute it
				$data .= call_user_func( $this->callback );
			}
		} else {
			// loop stack and append result of export method of each
			foreach ( $this->stack as $exportable ) {
				/* @var $exportable Pie_Easy_Exportable */
				$data .= $exportable->export();
			}
		}

		// any result?
		if ( !empty( $data ) ) {
			if ( $this->write( $data ) ) {
				$this->updated = true;
			}
		} else {
			// no content to write, remove it
			$this->remove();
		}

		// now try to update my children
		if ( $this->children->count() ) {
			// loop all and call update
			foreach( $this->children as $child ) {
				$child->update();
			}
		}
	}

	/**
	 * If given timestamp is more recent than file last modified time, update the file
	 *
	 * @param integer $timestamp Unix timestamp
	 * @return boolean
	 */
	public function refresh( $timestamp )
	{
		if ( $this->updated ) {
			return true;
		} else {
			// must be a number
			if ( is_numeric( $timestamp ) ) {
				// does file exist?
				if ( Pie_Easy_Files::cache($this->path)->is_readable() ) {
					// when was file last modified?
					$mtime = filemtime( $this->path );
					// is timestamp more recent?
					if ( (integer) $timestamp > $mtime ) {
						// yes, update it
						return $this->update();
					}
				} else {
					// doesn't exist, update it
					return $this->update();
				}
			}

			// did NOT refresh
			return false;
		}
	}

	/**
	 * Force update the file, ignoring any conditions
	 *
	 * @return boolean
	 */
	public function refresh_hard()
	{
		// update without any stat checks
		return $this->update();
	}

	/**
	 * Remove the export file
	 *
	 * @return boolean
	 */
	private function remove()
	{
		// mark as updated to avoid n+ delete attempts
		$this->updated = true;

		if ( Pie_Easy_Files::cache($this->path)->is_writable() ) {
			return unlink( $this->path );
		}
	}
}

/**
 * Pie Easy Export Exception
 *
 * @package PIE
 * @subpackage utils
 */
final class Pie_Easy_Export_Exception extends Exception {}

?>
