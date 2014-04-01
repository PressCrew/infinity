<?php
/**
 * Infinity Theme: shortcodes classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-api
 * @subpackage shortcodes
 * @since 1.0
 */

ICE_Loader::load(
	'components/shortcodes/component',
	'components/shortcodes/factory',
	'components/shortcodes/policy',
	'components/shortcodes/registry',
	'components/shortcodes/renderer'
);

//
// Helpers
//

/**
 * Initialize shortcodes environment
 *
 * @package Infinity-api
 * @subpackage shortcodes
 */
function infinity_shortcodes_init()
{
	// component policy
	$shortcodes_policy = ICE_Shortcode_Policy::instance();

	// enable component
	ICE_Scheme::instance()->enable_component( $shortcodes_policy );

	do_action( 'infinity_shortcodes_init' );
}

/**
 * Initialize shortcodes screen requirements
 *
 * @package Infinity-api
 * @subpackage shortcodes
 */
function infinity_shortcodes_init_screen()
{
	// init ajax OR screen reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		ICE_Shortcode_Policy::instance()->registry()->init_ajax();
		do_action( 'infinity_shortcodes_init_ajax' );
	} else {
		ICE_Shortcode_Policy::instance()->registry()->init_screen();
		do_action( 'infinity_shortcodes_init_screen' );
	}
}
