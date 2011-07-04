<?php
/**
 * PIE API: screens registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'screens/factory' );

/**
 * Make keeping track of screens easy
 *
 * @package PIE
 * @subpackage screens
 */
abstract class Pie_Easy_Screens_Registry extends Pie_Easy_Registry
{
	// nothing custom yet
}

?>
