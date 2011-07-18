<?php
/**
 * PIE API: widgets registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage widgets
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'widgets/factory' );

/**
 * Make keeping track of widgets easy
 *
 * @package PIE
 * @subpackage widgets
 */
abstract class Pie_Easy_Widgets_Registry extends Pie_Easy_Registry
{
	// nothing custom yet
}

?>
