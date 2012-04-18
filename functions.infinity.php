<?php
/**
 * Infinity Theme: theme functions
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @since 1.0
 */

/**
 * Set the Infinity path
 */
define( 'INFINITY_PATH', dirname( __FILE__ ) );

/**
 * Set the Infinity theme name (usually current theme/directory name)
 */
define( 'INFINITY_NAME', basename( INFINITY_PATH ) );

/**
 * Set the Infinity engine directory name
 */
define( 'INFINITY_ENGINE_DIR', 'engine' );

/**
 * To Infinity, and beyond! (sorry, had to do it)
 */
require_once( INFINITY_PATH . '/' . INFINITY_ENGINE_DIR . '/infinity.php' );

//
// At this point, Infinity is fully loaded and initialized,
// and your includes/setup.php has been loaded if applicable.
//
// So... get to work! (Unless you don't roll on Shabbos)
//

?>