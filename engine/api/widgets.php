<?php
/**
 * Infinity Theme: widgets classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-api
 * @subpackage widgets
 * @since 1.0
 */

ICE_Loader::load(
	'components/widgets/component',
	'components/widgets/factory',
	'components/widgets/policy',
	'components/widgets/registry',
	'components/widgets/renderer'
);

//
// Helpers
//

/**
 * Display a widget
 *
 * @package Infinity-api
 * @subpackage widgets
 * @param string $widget_name
 * @param boolean $output
 * @return string|false
 */
function infinity_widget( $widget_name, $output = true )
{
	return ICE_Policy::widgets()->registry()->get($widget_name)->render( $output );
}

/**
 * Initialize widgets screen requirements
 *
 * @package Infinity-api
 * @subpackage widgets
 */
function infinity_widgets_init_screen()
{
	// init ajax OR widget reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		ICE_Policy::widgets()->registry()->init_ajax();
		do_action( 'infinity_widgets_init_ajax' );
	} else {
		ICE_Policy::widgets()->registry()->init_screen();
		do_action( 'infinity_widgets_init_widget' );
	}
}
