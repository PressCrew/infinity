<?php
/**
 * PIE Framework enqueue helpers class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage enqueue
 * @since 1.0
 */

Pie_Easy_Loader::load( 'files' );

/**
 * Make enqueing assets Easy
 */
final class Pie_Easy_Enqueue
{
	/**
	 * Enqueue all stylesheets in a directory
	 *
	 * @param string $dir Absolute path the to the directory
	 * @param string $uri URI of the directory
	 * @param string $prefix A prefix for the enqueued handle
	 * @param string $version
	 */
	static public function styles( $dir, $uri, $prefix = null, $version = null )
	{
		// get all css files from dir
		$files = Pie_Easy_Files::list_filtered( $dir, '/\.css$/' );

		// enqueue each one
		foreach ( $files as $file ) {
			wp_enqueue_style(
				$prefix . str_replace( '.css', '', $file),
				sprintf( '%s/%s', $uri, $file ),
				null,
				$version
			);
		}
	}

	/**
	 * Enqueue all javascript source files in a directory
	 *
	 * @param string $dir Absolute path the to the directory
	 * @param string $uri URI of the directory
	 * @param string $prefix A prefix for the enqueued handle
	 * @param string $version
	 */
	static public function scripts( $dir, $uri, $prefix = null, $version = null )
	{
		// get all css files from dir
		$files = Pie_Easy_Files::list_filtered( $dir, '/\.js$/' );

		// enqueue each one
		foreach ( $files as $file ) {
			wp_enqueue_script(
				$prefix . str_replace( '.js', '', $file),
				sprintf( '%s/%s', $uri, $file ),
				array( 'jquery' ),
				$version
			);
		}
	}
}

?>
