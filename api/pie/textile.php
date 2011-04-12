<?php
/**
 * PIE textile class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage parsers
 * @since 1.0
 */

/**
 * Load the markdown lib
 */
require_once
	PIE_EASY_VENDORS_DIR .
	DIRECTORY_SEPARATOR . 'textile' .
	DIRECTORY_SEPARATOR . 'classTextile.php';

/**
 * Make Textile parsing easy
 *
 * @package pie
 * @subpackage parsers
 */
final class Pie_Easy_Textile
{
	/**
	 * Parse textile markup
	 *
	 * @param string $text
	 * @return string
	 */
	public static function parse( $text )
	{
		$parser = new Textile();
		return $parser->TextileThis( $text );
	}
}

?>
