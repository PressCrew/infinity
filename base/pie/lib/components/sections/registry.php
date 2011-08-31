<?php
/**
 * PIE API: sections registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage sections
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'components/sections/factory' );

/**
 * Make keeping track of sections easy
 *
 * @package PIE-components
 * @subpackage sections
 */
abstract class Pie_Easy_Sections_Registry extends Pie_Easy_Registry
{
	// nothing custom yet
}

?>
