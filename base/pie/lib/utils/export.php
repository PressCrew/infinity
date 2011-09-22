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

Pie_Easy_Loader::load( 'utils/files' );

/**
 * Make exporting dynamic files easy
 *
 * @todo this should extend Pie_Easy_File
 * @package PIE
 * @subpackage utils
 * @uses Pie_Easy_Export_Exception
 */
class Pie_Easy_Export extends Pie_Easy_Base
{
	static private $populated = false;
	static private $upload_dir;
	static private $upload_url;
	static private $export_dir;
	static private $export_url;

	private $path;
	private $url;
	private $callback;
	private $updated = false;

	/**
	 * Constructor
	 *
	 * @param string $filename Name of file to manage RELATIVE to export dir
	 * @param string|array $callback Callback used to update file contents
	 */
	public function __construct( $filename, $callback )
	{
		// make sure dir info is populated
		$this->populate_dir_props();

		// determine file path and url
		$this->path = self::$export_dir . Pie_Easy_Files::path_build( $filename );
		$this->url = self::$export_url . '/' . $filename;
		$this->callback = $callback;
	}

	public function __get( $name )
	{
		switch( $name ) {
			case 'path':
			case 'url':
				return $this->$name;
			default:
				return parent::__get( $name );
		}
	}

	public function __isset( $name )
	{
		switch( $name ) {
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
	 * @ignore
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
				self::$upload_dir = $upload_dir['basedir'];
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
			if ( Pie_Easy_Files::cache(self::$export_dir)->is_writable() ) {
				// get file instance
				$file = Pie_Easy_Files::cache( $this->path );
				// if file already exists, puke if not writeable
				if ( $file->exists() && !$file->is_writable() ) {
					throw new Pie_Easy_Export_Exception(
						'Unable to write to the file: ' . $this->path );
				}
				// write it
				return file_put_contents( $this->path, $data );
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
	 * Update the export file with result of callback execution
	 *
	 * @return boolean
	 */
	public function update()
	{
		// get result of callback
		$result = call_user_func( $this->callback );

		// any result?
		if ( $result ) {
			if ( $this->write( $result ) ) {
				$this->updated = true;
				return true;
			}
		} else {
			// no content to write, remove it
			return $this->remove();
		}

		return false;
	}

	/**
	 * If given timestamp is more recent than file last modified time, update the file
	 *
	 * @param integer $timestamp Unix timestamp
	 * @param boolean $force Set to true to force a refresh even if file has been updated already
	 * @return boolean
	 */
	public function refresh( $timestamp, $force = false )
	{
		if ( $this->updated && !$force ) {
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
					$this->update();
				}
			}

			// did NOT refresh
			return false;
		}
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
