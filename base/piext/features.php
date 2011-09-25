<?php
/**
 * Infinity Theme: features classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-components
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/features' );

/**
 * Infinity Theme: features policy
 *
 * @package Infinity-components
 * @subpackage features
 */
class Infinity_Features_Policy extends Pie_Easy_Features_Policy
{
	/**
	 * @return Pie_Easy_Features_Policy
	 */
	static public function instance()
	{
		self::$calling_class = __CLASS__;
		return parent::instance();
	}
	
	/**
	 */
	final public function get_api_slug()
	{
		return 'infinity_theme';
	}

	/**
	 */
	final public function enable_styling()
	{
		return ( !is_admin() );
	}

	/**
	 */
	final public function enable_scripting()
	{
		return ( !is_admin() );
	}

	/**
	 * @return Infinity_Features_Registry
	 */
	final public function new_registry()
	{
		return new Infinity_Features_Registry();
	}

	/**
	 * @return Infinity_Exts_Feature_Factory
	 */
	final public function new_factory()
	{
		return new Infinity_Exts_Feature_Factory();
	}

	/**
	 * @return Infinity_Features_Renderer
	 */
	final public function new_renderer()
	{
		return new Infinity_Features_Renderer();
	}
}

/**
 * Infinity Theme: features registry
 *
 * @package Infinity-components
 * @subpackage features
 */
class Infinity_Features_Registry extends Pie_Easy_Features_Registry
{
	// nothing custom yet
}

/**
 * Infinity Theme: feature factory
 *
 * @package Infinity-extensions
 * @subpackage features
 */
class Infinity_Exts_Feature_Factory extends Pie_Easy_Features_Factory
{
	// nothing custom yet
}

/**
 * Infinity Theme: features renderer
 *
 * @package Infinity-components
 * @subpackage features
 */
class Infinity_Features_Renderer extends Pie_Easy_Features_Renderer
{
	// nothing custom yet
}

//
// Helpers
//

/**
 * Display a feature
 *
 * @package Infinity-components
 * @subpackage features
 * @param string $feature_name
 * @param boolean $output
 * @return string|false
 */
function infinity_feature( $feature_name, $output = true )
{
	// is feature supported?
	if ( current_theme_supports( $feature_name ) ) {
		// yes, render it
		return Infinity_Features_Policy::instance()->registry()->get($feature_name)->render( $output );
	} else {
		// not supported
		return false;
	}
}

/**
 * Initialize features environment
 *
 * @package Infinity-components
 * @subpackage features
 */
function infinity_features_init()
{
	// component policies
	$features_policy = Infinity_Features_Policy::instance();

	// enable components
	Pie_Easy_Scheme::instance()->enable_component( $features_policy );

	do_action( 'infinity_features_init' );
}

/**
 * Initialize features screen requirements
 *
 * @package Infinity-components
 * @subpackage features
 */
function infinity_features_init_screen()
{
	// init ajax OR screen reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		Infinity_Features_Policy::instance()->registry()->init_ajax();
		do_action( 'infinity_features_init_ajax' );
	} else {
		Infinity_Features_Policy::instance()->registry()->init_screen();
		do_action( 'infinity_features_init_screen' );
	}
}

?>
