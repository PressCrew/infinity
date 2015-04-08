<?php
/**
 * Infinity Theme: buddypress-docs compat
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

//
// Filters
//

/**
 * Return path to custom BuddyPress Docs template if it exists.
 *
 * @param string $template_path
 * @param string $template
 * @return string
 */
function infinity_bp_docs_locate_template_filter( $template_path, $template )
{
	// custom path to check
	$infinity_path = INFINITY_THEME_PATH . '/templates/plugins/buddypress-docs/' . $template;

	// does custom template file exist?
	if ( true === file_exists( $infinity_path ) ) {
		// yes, return it
		return $infinity_path;
	}

	// return original by default
	return $template_path;
}
add_filter( 'bp_docs_locate_template', 'infinity_bp_docs_locate_template_filter', 99, 2 );

//
// Compat
//

/**
 * Don't allow theme compatibility mode to kick in by default.
 *
 * @since 1.2
 */
add_filter( 'bp_docs_do_theme_compat', '__return_false' );
