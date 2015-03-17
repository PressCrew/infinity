<?php
/**
 * Infinity Theme: includes
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2013 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.0
 */

/**
 * Include custom functionality
 */
require_once INFINITY_INC_PATH . '/base.php';
require_once INFINITY_INC_PATH . '/menus.php';
require_once INFINITY_INC_PATH . '/sidebars.php';
require_once INFINITY_INC_PATH . '/widgets.php';
require_once INFINITY_INC_PATH . '/slider.php';
require_once INFINITY_INC_PATH . '/comments.php';
require_once INFINITY_INC_PATH . '/templatetags.php';
require_once INFINITY_INC_PATH . '/walkers.php';
require_once INFINITY_INC_PATH . '/options.php';

/**
 * Maybe include BuddyPress functionality
 */
if ( true === class_exists( 'BP_Component', false ) ) {
	// BP component exists, assume it's loaded
	require_once INFINITY_INC_PATH . '/buddypress.php';
}

/**
 * Maybe include Commons In a Box functionality
 *
 * @todo this is likely not a permanent strategy. just trying to move things along!
 */
if ( true === class_exists( 'Commons_In_A_Box', false ) ) {
	// commons in a box loader exists, assume it's loaded
	require_once INFINITY_INC_PATH . '/cbox/setup.php';
}

////////////////////////////////////////////////////
//
// IMPORTANT:
//
// 1. If you are working on a fork, add additional
//    requires BELOW this comment.
//
// 2. Please DO NOT put any functions or logic in
//    this file. Its for requires ONLY!
//
////////////////////////////////////////////////////
