<?php
/**
 * Infinity Theme: plugins compat
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

// is bbPress >= v2 loaded?
if ( true === function_exists( 'bbpress' ) ) {
	// yes, load support
	require_once INFINITY_INC_PATH . '/plugins/bbpress.php';
}

// is buddypress-docs loaded?
if ( true === function_exists( 'bp_docs_init' ) ) {
	// yes, load support
	require_once INFINITY_INC_PATH . '/plugins/buddypress-docs.php';
}

// is buddypress-docs-wiki loaded?
if ( true === function_exists( 'bpdw_init' ) ) {
	// yes, load support
	require_once INFINITY_INC_PATH . '/plugins/buddypress-docs-wiki.php';
}
