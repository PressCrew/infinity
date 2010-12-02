<?php
/**
 * PIE Framework file system helper class file
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
				throw new Exception( 'Unable to open the directory: ' . $dir );
			}
		} else {
			throw new Exception( 'The directory does not exist: ' . $dir );
		}
	}
}

?>
