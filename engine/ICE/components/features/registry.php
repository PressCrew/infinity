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
	 * Feature types that *might* have an options file.
	 *
	 * @var array
	 */
	private $opt_files = array(
		'bp/fb-autoconnect',
		'echo',
		'header-logo'
	);
	
	/**
	 */
	protected function load_config_array( $comp_name, $settings )
	{
		// init sub options array
		$sub_options = array();

		// does this feature's type use sub option files?
		if ( true === in_array( $settings[ 'type' ], $this->opt_files ) ) {
			// yep, try to load it
			$file_options = $this->load_sub_options_file( $settings[ 'type' ] );
			// get any?
			if ( $file_options ) {
				// yep, merge em over top of existing
				$sub_options = array_merge( $sub_options, $file_options );
			}
		}
		
		// did this feature pass an array of sub options?
		if (
			true === isset( $settings['options'] ) &&
			true === is_array( $settings['options'] )
		) {
			// yep, merge them over top of existing
			$sub_options = array_merge( $sub_options, $settings['options'] );
		}

		// kill options reference in settings array no matter what
		unset( $settings['options'] );

		// load like a regular component first
		if ( true === parent::load_config_array( $comp_name, $settings ) ) {

			// has any sub options?
			if (
				0 < count( $sub_options ) &&
				true === $this->policy()->options() instanceof ICE_Policy
			) {
				// yes, call sub options loader
				$this->load_sub_options( $comp_name, $sub_options );
			}

			// feature config loaded
			return true;

		} else {

			// feature config *not* loaded
			return false;
			
		}
	}

	/**
	 * Load the sub-options file for the given extension.
	 *
	 * @param string $ext
	 * @return array|false
	 */
	private function load_sub_options_file( $ext )
	{
		// try to get path
		$path = ICE_Ext_Loader::instance()->locate_file( 'features/' .  $ext, 'options.php' );

		// get a path?
		if ( $path ) {
			// yes, try to include
			$file_options = include( $path );
			// get an array?
			if ( is_array( $file_options ) ) {
				// yep, return it
				return $file_options;
			}
		}

		// file not loaded
		return false;
	}

	/**
	 * Load sub options for a feature.
	 *
	 * @param string $feature
	 * @param array $options
	 */
	private function load_sub_options( $feature, $options )
	{
		foreach( $options as $option => $settings ) {
			// call sub option loader
			$this->load_sub_option( $feature, $option, $settings );
		}
	}

	/**
	 * Load one sub option for a feature.
	 *
	 * @param string $feature
	 * @param string $option
	 * @param array $settings
	 */
	private function load_sub_option( $feature, $option, $settings )
	{
		// is this the feature option defaults?
		if ( self::OPT_DEF_NAME === $option ) {
			// yep, set em
			$this->load_default_option( $feature, $settings );
		} else {
			// inject feature into settings array
			$settings[ 'feature' ] = $feature;
			// call special feature option loader
			$this->policy()->options()->registry()->load_feature_option( $option, $settings );
		}
	}

	/**
	 * Load default option config for a feature.
	 *
	 * @param string $feature
	 * @param array $settings
	 */
	private function load_default_option( $feature, $settings )
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
