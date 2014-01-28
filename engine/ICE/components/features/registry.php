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
	 */
	protected function load_config_section( $section_name, $section_array )
	{
		// load like a regular section first
		if ( true === parent::load_config_section( $section_name, $section_array ) ) {

			// feature name is section name
			$feature = $section_name;

			// does theme support this feature?
			if ( true === current_theme_supports( $feature ) ) {
				// has any sub options?
				if (
					true === isset( $section_array['options'] ) &&
					true === is_array( $section_array['options'] ) &&
					true === $this->policy()->options() instanceof ICE_Policy
				) {
					// yes, loop all sub options
					foreach( $section_array['options'] as $option => $option_config ) {
						// inject feature atts into config array
						$option_config[ 'feature' ] = $feature;
						// try to load it
						$this->policy()->options()->registry()->load_feature_option( $option, $option_config );
					}
				}
			}

			// feature config loaded
			return true;

		} else {

			// feature config *not* loaded
			return false;
			
		}
	}
}
