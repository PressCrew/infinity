<?php
/**
 * PIE API: base visitor interface file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

/**
 * Make performing operations on lists or trees of objects easy
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Visitor
{
	/**
	 * Visit a visitable class instance
	 *
	 * @param Pie_Easy_Visitable $visitable
	 */
	public function visit( Pie_Easy_Visitable $visitable );
}

/**
 * Implement this interface to accept visits from visitors
 *
 * @package PIE
 * @subpackage base
 */
interface Pie_Easy_Visitable
{
	/**
	 * Accept a visitor class instance
	 * 
	 * @param Pie_Easy_Visitor $visitor
	 */
	public function accept( Pie_Easy_Visitor $visitor );
}

?>
