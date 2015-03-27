<?php
/**
 * Infinity Theme: widgets
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson & CUNY Academic Commons
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

/**
 * Don't allow WordPress to move widgets over to this theme,
 * as it messes with our own widget setup routine.
 */
remove_action( 'after_switch_theme', '_wp_sidebars_changed' );
add_action( 'after_switch_theme', 'retrieve_widgets' );