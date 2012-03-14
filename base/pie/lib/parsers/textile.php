<?php
/**
 * PIE API: textile class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage parsers
 * @since 1.0
 */

/**
 * Load the markdown lib
 */
require_once PIE_EASY_VENDORS_DIR . '/textile/classTextile.php';

/**
 * Make Textile parsing easy
 *
 * @package PIE
 * @subpackage parsers
 */
final class Pie_Easy_Textile extends Pie_Easy_Base
{
	/**
	 * Parse textile markup and return HTML
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
