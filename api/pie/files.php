<?php
/**
 * PIE file system helper class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage files
 * @since 1.0
 */

/**
 * Make the File System Easy
 */
final class Pie_Easy_Files
{
	/**
	 * Split a path at forward '/' OR backward '\' slashes
	 * 
	 * @param string $path
	 * @return array
	 */
	public static function path_split ( $path )
	{
		return preg_split( '/\/|\\\/', $path, null, PREG_SPLIT_NO_EMPTY );
	}

	/**
	 * Pop the last file off the end of a path
	 *
	 * @param string $path
	 * @return string
	 */
	public static function path_pop ( $path )
	{
		return array_pop( self::path_split( $path ) );
	}

	/**
	 * Build a filesytem path from an array of file names
	 *
	 * @param array $file_names,...
	 * @param boolean $relative
	 * @return string
	 */
	public static function path_build ( $file_names, $relative = false )
	{
		if ( !is_array( $file_names ) ) {
			$file_names = func_get_args();
			if ( func_get_arg( func_num_args() - 1 ) === true ) {
				$relative = true;
			} else {
				$relative = false;
			}
		}

		if ( $relative ) {
			$prefix = null;
		} else {
			$prefix = DIRECTORY_SEPARATOR;
		}

		return $prefix . implode( DIRECTORY_SEPARATOR, $file_names );
	}

	/**
	 * Normalize a filesystem path
	 *
	 * This will remove useless slashes and convert invalid directory separators
	 * with the correct separator for the local system
	 *
	 * @param string $path
	 * @return string
	 */
	public static function path_normalize( $path )
	{
		// is this a relative path?
		if ( $path{0} == '/' || $path{0} == '\\' ) {
			$relative = false;
		} else {
			$relative = true;
		}

		// return the path
		return self::path_build( self::path_split( $path ), $relative );
	}

	/**
	 * List all files in a directory filtered by a regular expression
	 *
	 * @param string $dir Absolute path to directory
	 * @param string $regex Valid PCRE expression
	 * @param boolean $absolute Set to true to return abolute path to file
	 * @return array
	 */
	public function list_filtered( $dir, $regex, $absolute = false )
	{
		// does the directory exist?
		if ( is_dir( $dir ) ) {
			// try to open the dir
			$dh = opendir( $dir );
			// check that handle is valid
			if ( $dh ) {
				// list of files to return
				$return_files = array();
				// loop through and add only files that match regex to list
				while (($file = readdir($dh)) !== false) {
					// check regex
					if ( preg_match($regex, $file) ) {
						// build file path
						$file_path = ( $absolute ) ? $dir . DIRECTORY_SEPARATOR . $file : $file;
						// push onto return array
						$return_files[$file] = $file_path;
					}
				}
				// destroy handle
				closedir($dh);
				// sort the files (by key)
				ksort( $return_files );
				// done
				return $return_files;
			} else {
				throw new Pie_Easy_Files_Exception( 'Unable to open the directory: ' . $dir );
			}
		} else {
			throw new Pie_Easy_Files_Exception( 'The directory does not exist: ' . $dir );
		}
	}
}

/**
 * Pie Easy File Exception
 */
final class Pie_Easy_Files_Exception extends Exception {}

?>
