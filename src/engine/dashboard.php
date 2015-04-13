<?php
/**
 * Infinity Theme: dashboard functionality
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

// is this the admin area?
if ( true === is_admin() ) {
	// load requirements
	require_once INFINITY_DASHBOARD_PATH . '/cpanel.php';
	require_once INFINITY_DASHBOARD_PATH . '/support.php';
	require_once INFINITY_DASHBOARD_PATH . '/loader.php';
}