<?php
/**
 * PIE AJAX helpers class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage ajax
 * @since 1.0
 */

/**
 * Make AJAX Easy
 */
final class Pie_Easy_Ajax
{
	/**
	 * Delimiter on which to split AJAX responses
	 *
	 * @var string
	 */
	const RESPONSE_DELIMETER = '[[[s]]]';

	/**
	 * Print a response to an ajax request and die
	 *
	 * @param mixed $param,... Unlimited number of SCALAR values to join with the response delimeter
	 */
	public static function response( $param = null )
	{
		$args = func_get_args();
		die( join( self::RESPONSE_DELIMETER, $args ) );
	}

	/**
	 * Generate a "standard" AJAX response and die
	 *
	 * A standard AJAX response for our purposes is a delimited string containing
	 * a response code, message, and content
	 *
	 * @param integer $code A response code
	 * @param string $message Optional response message
	 * @param string $content Optional response content
	 * @return string
	 */
	public static function responseStd( $code, $message = null, $content = null )
	{
		return self::response( $code, $message, $content );
	}
}

?>
