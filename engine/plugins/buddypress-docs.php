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
// Compat
//

/**
 * Don't allow theme compatibility mode to kick in by default.
 *
 * @since 1.2
 */
add_filter( 'bp_docs_do_theme_compat', '__return_false' );
