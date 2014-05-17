<?php
/**
 * Infinity Theme: features classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-api
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load(
	'components/features/component',
	'components/features/policy',
	'components/features/registry'
);

//
// Helpers
//

/**
 * Display a feature
 *
 * @package Infinity-api
 * @subpackage features
 * @param string $feature_name
 * @param boolean $output
 * @return string|false
 */
function infinity_feature( $feature_name, $output = true )
{
	// is feature supported?
	if ( current_theme_supports( $feature_name ) ) {
		// yes, render it
		return ICE_Policy::features()->registry()->get($feature_name)->render( $output );
	} else {
		// not supported
		return false;
	}
}

/**
 * Fetch a feature
 *
 * @package Infinity-api
 * @subpackage features
 * @param string $feature_name
 * @return ICE_Feature|false
 */
function infinity_feature_fetch( $feature_name )
{
	// is feature supported?
	if ( current_theme_supports( $feature_name ) ) {
		// yes, return it
		return ICE_Policy::features()->registry()->get($feature_name);
	} else {
		// not supported
		return false;
	}
}
