<?php
/**
 * BP Tasty theme functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package functions
 * @since 1.0
 */

define( 'BP_TASTY_NAME', 'bp-tasty' );
define( 'BP_TASTY_THEME_DIR', STYLESHEETPATH );
define( 'BP_TASTY_THEME_URL', get_stylesheet_directory_uri() );
define( 'BP_TASTY_API_DIR', BP_TASTY_THEME_DIR . '/api' );
define( 'BP_TASTY_EXTRAS_DIR',  get_theme_root() . '/bp-tasty-extras' );

require_once( BP_TASTY_API_DIR . '/skinning.php' );

//function remove_header_support() {
//	remove_action( 'init', 'bp_dtheme_add_custom_header_support' );
//}
//add_action( 'init', 'remove_header_support', 9 );

?>
