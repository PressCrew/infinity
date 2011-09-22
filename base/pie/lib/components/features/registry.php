<?php
/**
 * PIE API: features registry
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/registry', 'components/features/factory' );

/**
 * Make keeping track of features easy
 *
 * @package PIE-components
 * @subpackage features
 */
abstract class Pie_Easy_Features_Registry extends Pie_Easy_Registry
{
	// nothing custom yet
}

?>
