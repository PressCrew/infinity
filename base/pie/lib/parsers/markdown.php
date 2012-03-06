<?php
/**
 * PIE API: markdown class file
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
 * Disable automatic WP post parsing
 * @internal
 */
@define( 'MARKDOWN_WP_POSTS', false );

/**
 * Disable automatic WP comment parsing
 * @internal
 */
@define( 'MARKDOWN_WP_COMMENTS', false );

/**
 * Load the markdown lib
 */
require_once PIE_EASY_VENDORS_DIR . '/markdown/markdown.php';

/**
 * Make Markdown parsing easy
 *
 * @package PIE
 * @subpackage parsers
 */
final class Pie_Easy_Markdown extends Pie_Easy_Base
{
	/**
	 * Parse markdown markup and return HTML
	 *
	 * @param string $text
	 * @return string
	 */
	public static function parse( $text )
	{
		return Markdown( $text );
	}
}

?>
