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
	 * Option name which contains default config.
	 */
	const OPT_DEF_NAME = 'defaults';

	/**
	 * Default option config for features that have one.
	 *
	 * @var array
	 */
	private $opt_defaults = array();

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
					// yes, call sub options loader
					$this->load_sub_options( $feature, $section_array['options'] );
				}
			}

			// feature config loaded
			return true;

		} else {

			// feature config *not* loaded
			return false;
			
		}
	}

	/**
	 * Load sub options for a feature.
	 *
	 * @param string $feature
	 * @param array $options
	 */
	private function load_sub_options( $feature, $options )
	{
		foreach( $options as $option => $option_config ) {
			// call sub option loader
			$this->load_sub_option( $feature, $option, $option_config );
		}
	}

	/**
	 * Load one sub option for a feature.
	 *
	 * @param string $feature
	 * @param string $option
	 * @param array $option_config
	 */
	private function load_sub_option( $feature, $option, $option_config )
	{
		// is this the feature option defaults?
		if ( self::OPT_DEF_NAME === $option ) {
			// yep, set em
			$this->load_default_option( $feature, $option_config );
		} else {
			// inject feature atts into config array
			$option_config[ 'feature' ] = $feature;
			// call special feature option loader
			$this->policy()->options()->registry()->load_feature_option( $option, $option_config );
		}
	}

	/**
	 * Load default option config for a feature.
	 *
	 * @param string $feature
	 * @param array $option_config
	 */
	private function load_default_option( $feature, $option_config )
	{
		// already have some defaults set?
		if ( isset( $this->opt_defaults[ $feature ] ) ) {
			// yes, merge on top of existing
			$option_config = array_merge( $this->opt_defaults[ $feature ], $option_config );
		}

		// set defaults
		$this->opt_defaults[ $feature ] = $option_config;
	}

	/**
	 * Get default option config for a feature.
	 *
	 * @param string $feature
	 * @return array|false
	 */
	public function get_default_option( $feature )
	{
		if ( isset( $this->opt_defaults[ $feature ] ) ) {
			return $this->opt_defaults[ $feature ];
		} else {
			return false;
		}
	}

}
