<?php
/**
 * PIE API: widgets registry
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage widgets
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'components/widgets/factory' );

/**
 * Make keeping track of widgets easy
 *
 * @package PIE-components
 * @subpackage widgets
 */
abstract class Pie_Easy_Widgets_Registry extends Pie_Easy_Registry
{
	// nothing custom yet
}

?>
