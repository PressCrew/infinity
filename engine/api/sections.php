<?php
/**
 * Infinity Theme: sections classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-api
 * @subpackage sections
 * @since 1.0
 */

ICE_Loader::load(
	'components/sections/component',
	'components/sections/factory',
	'components/sections/policy',
	'components/sections/registry',
	'components/sections/renderer'
);

//
// Helpers
//

/**
 * Initialize sections screen requirements
 *
 * @package Infinity-api
 * @subpackage sections
 */
function infinity_sections_init_screen()
{
	// init ajax OR screen reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		ICE_Policy::sections()->registry()->init_ajax();
		do_action( 'infinity_sections_init_ajax' );
	} else {
		ICE_Policy::sections()->registry()->init_screen();
		do_action( 'infinity_sections_init_screen' );
	}
}
