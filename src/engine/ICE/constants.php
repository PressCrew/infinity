<?php
/**
 * ICE API: core constants file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @since 1.2
 */

/**
 * ICE API: version.
 */
define( 'ICE_VERSION', '1.2b2' );
/**
 * ICE API: library directory (3rd party).
 */
define( 'ICE_LIB_PATH', ICE_PATH . '/lib' );
/**
 * ICE API: extensions library directory.
 */
define( 'ICE_EXT_PATH', ICE_PATH . '/ext' );
/**
 * ICE API: cache get_stylesheet() call for performance.
 */
define( 'ICE_ACTIVE_THEME', get_stylesheet() );
/**
 * ICE API: cache is_admin() call for performance.
 */
define( 'ICE_IS_ADMIN', is_admin() );
/**
 * ICE API: cache doing ajax check for performance.
 */
define( 'ICE_IS_AJAX', defined( 'DOING_AJAX' ) );
/**
 * ICE API: will be true if dashboard interface is showing.
 */
define( 'ICE_IS_DASH', ( true === ICE_IS_ADMIN && false === ICE_IS_AJAX ) );
/**
 * ICE API: will be true if blog (front end) is showing.
 */
define( 'ICE_IS_BLOG', ( false === ICE_IS_ADMIN && false === ICE_IS_AJAX ) );
