<?php
/**
 * ICE API: features registry
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'base/registry', 'components/features/factory' );

/**
 * Make keeping track of features easy
 *
 * @package ICE-components
 * @subpackage features
 */
abstract class ICE_Feature_Registry extends ICE_Registry
{
	/**
	 * Load one component into registry from a single parsed ini section (array)
	 *
	 * @param string $section_name
	 * @param array $section_array
	 * @return boolean
	 */
	protected function load_config_section( $section_name, $section_array )
	{
		// sub option syntax?
		if ( strpos( $section_name, self::SUB_OPTION_DELIM ) ) {
			// yes, split at special delim
			$parts = explode( self::SUB_OPTION_DELIM, $section_name );
			// feature is the first part
			$feature = $parts[0];
		} else {
			// feature name is section name
			$feature = $section_name;
		}

		// does theme support this feature?
		if ( current_theme_supports( $feature ) ) {
			// yes!
			return parent::load_config_section( $section_name, $section_array );
		}

		// feature config *not* loaded
		return false;
	}
}

?>
