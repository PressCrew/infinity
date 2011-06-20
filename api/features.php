<?php
/**
 * Infinity Theme: features classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'features', 'utils/files' );

/**
 * Infinity Theme: features policy
 *
 * @package api
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
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	final public function get_api_slug()
	{
		return 'infinity_theme';
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

	/**
	 * @param string $ext
	 * @return Pie_Easy_Features_Feature
	 */
	final public function load_ext( $ext )
	{
		return infinity_load_extension( $this->get_handle(), $ext );
	}
}

/**
 * Infinity Theme: features registry
 *
 * @package api
 * @subpackage features
 */
class Infinity_Features_Registry extends Pie_Easy_Features_Registry
{
	// nothing custom yet
}

/**
 * Infinity Theme: feature factory
 *
 * @package api
 * @subpackage exts
 */
class Infinity_Exts_Feature_Factory extends Pie_Easy_Features_Factory
{
	// nothing custom yet
}

/**
 * Infinity Theme: features renderer
 *
 * @package api
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
 * @param string $feature_name
 * @return mixed
 */
function infinity_feature( $feature_name, $output = true )
{
	return Infinity_Features_Policy::instance()->registry()->get($feature_name)->render( $output );
}

/**
 * Initialize features environment
 */
function infinity_features_init( $theme = null )
{
	// component policies
	$features_policy = Infinity_Features_Policy::instance();

	// enable components
	Pie_Easy_Scheme::instance($theme)->enable_component( $features_policy );

	do_action( 'infinity_features_init' );
}

/**
 * Initialize features screen requirements
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
