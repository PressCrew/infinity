<?php
/**
 * PIE API: shortcodes registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage shortcodes
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'components/shortcodes/factory' );

/**
 * Make keeping track of shortcodes easy
 *
 * @package PIE-components
 * @subpackage shortcodes
 */
abstract class Pie_Easy_Shortcodes_Registry extends Pie_Easy_Registry
{
	// nothing custom yet
}

?>
