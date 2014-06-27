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
 * @param string $name
 * @param string $group
 * @return void|false
 */
function infinity_feature( $name, $group = 'default' )
{
	// is feature supported?
	if ( current_theme_supports( ICE_SLUG . ICE_Feature::API_DELIM . $group, $name ) ) {
		// yes, render it
		return ICE_Policy::features()->registry()->get( $name, $group )->render();
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
 * @param string $name
 * @param string $group
 * @return ICE_Feature|false
 */
function infinity_feature_fetch( $name, $group = 'default' )
{
	// is feature supported?
	if ( current_theme_supports( ICE_SLUG . ICE_Feature::API_DELIM . $group, $name ) ) {
		// yes, render it
		return ICE_Policy::features()->registry()->get( $name, $group );
	} else {
		// not supported
		return false;
	}
}
