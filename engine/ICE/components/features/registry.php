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

ICE_Loader::load(
	'base/registry',
	'components/features/factory'
);

/**
 * Make keeping track of features easy
 *
 * @package ICE-components
 * @subpackage features
 */
class ICE_Feature_Registry extends ICE_Registry
{
	/**
	 * Default option config for features that have one.
	 *
	 * @var array
	 */
	private $opt_defaults = array();

	/**
	 */
	public function register( $name, $settings )
	{
		// load like a regular component first
		if ( true === parent::register( $name, $settings ) ) {

			// get extension settings
			$ext_settings = $this->policy()->extensions()->get_settings( $settings[ 'type' ] );

			// does this feature's type have bundled options?
			if ( true === isset( $ext_settings[ 'options' ] ) ) {
				// yep, loop all bundled options
				foreach( $ext_settings[ 'options' ] as $so_name => $so_settings ) {
					// register the sub option settings
					$this->register_suboption( $name, $so_name, $so_settings );
				}
			}

			// feature registered
			return true;
		}

		// feature not registered
		return false;
	}

	/**
	 * Register one sub option for a feature.
	 *
	 * @param string $feature
	 * @param string $option
	 * @param array $settings
	 */
	public function register_suboption( $feature, $option, $settings )
	{
		// call special feature option loader
		return $this->policy()->options()->registry()->register_feature_option( $feature, $option, $settings );
	}

	/**
	 * Register suboption default setting values for a feature.
	 *
	 * @param string $feature
	 * @param array $settings
	 */
	public function register_suboption_defaults( $feature, $settings )
	{
		// already have some defaults set?
		if ( isset( $this->opt_defaults[ $feature ] ) ) {
			// yes, merge on top of existing
			$settings = array_merge( $this->opt_defaults[ $feature ], $settings );
		}

		// set defaults
		$this->opt_defaults[ $feature ] = $settings;
	}

	/**
	 * Get default suboption values for given feature name.
	 *
	 * @param string $name
	 * @return array|false
	 */
	public function get_suboption_defaults( $name )
	{
		if ( isset( $this->opt_defaults[ $name ] ) ) {
			return $this->opt_defaults[ $name ];
		} else {
			return false;
		}
	}

}
