<?php
/**
 * Infinity Theme: BuddyPress functions
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson & CBOX Team
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.0
 */

/**
 * Create an excerpt
 *
 * Uses bp_create_excerpt() when available. Otherwise falls back on a very
 * rough approximation, ignoring the fancy params passed.
 *
 * @return string
 */
function infinity_bp_create_excerpt( $text, $length = 425, $options = array() )
{
	// does bp function exist?
	if ( function_exists( 'bp_create_excerpt' ) ) {
		// yes, use it
		return bp_create_excerpt( $text, $length, $options );
	} else {
		// no, wing it
		return substr( $text, 0, $length ) . ' [&hellip;]';
	}
}