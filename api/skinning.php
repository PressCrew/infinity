<?php
/**
 * BP Tasty theme API templating functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage templating
 * @since 1.0
 */

// TODO this will be dynamic in the future
define( 'BP_TASTY_SKIN_NAME', 'minimal' );
define( 'BP_TASTY_EXTRAS_SKIN_DIR', BP_TASTY_EXTRAS_DIR . '/skins' );
define( 'BP_TASTY_ACTIVE_SKIN_DIR', BP_TASTY_EXTRAS_SKIN_DIR . '/' . BP_TASTY_SKIN_NAME );

/**
 * Filter located template from bp_core_load_template
 *
 * @see bp_core_load_template()
 * @param string $located_template
 * @param array $template_names
 * @return string
 */
function bp_tasty_filter_template( $located_template, $template_names )
{
	// template already located, skip
	if ( empty( $located_template ) ) {
		return bp_tasty_locate_theme_template( $template_names );
	} else {
		return $located_template;
	}
}
add_filter( 'bp_located_template', 'bp_tasty_filter_template', 10, 2 );

/**
 * Check if template exists in custom style (skin) path
 *
 * @param array $template_names
 * @param boolean $load Auto load template if set to true
 * @return string
 */
function bp_tasty_locate_template( $template_names, $load = false )
{
	// did we get an array?
	if ( is_array( $template_names ) ) {

		// loop through all templates
		foreach ( $template_names as $template_name ) {

			// prepend all template names with the active skin dir
			$located_template = BP_TASTY_ACTIVE_SKIN_DIR . '/' . $template_name;

			// does it exist?
			if ( file_exists( $located_template ) ) {
				// load it?
				if ($load) {
					load_template( $located_template );
				}
				// return the located template path
				return $located_template;
			}
		}
	}

	// didn't find a template
	return '';
}

function bp_tasty_load_template( $template_name )
{
	if ( !is_array( $template_name ) ) {
		$template_name = array( $template_name );
	}

	return bp_tasty_locate_template( $template_name, true );
}

?>
