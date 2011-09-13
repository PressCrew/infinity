<?php
/**
 * Infinity Theme: screens classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage screens
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/screens' );

/**
 * Infinity Theme: screens policy
 *
 * @package Infinity
 * @subpackage screens
 */
class Infinity_Screens_Policy extends Pie_Easy_Screens_Policy
{
	/**
	 * @return Pie_Easy_Screens_Policy
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
	 * @ignore
	 * @return boolean
	 */
	final public function enable_styling()
	{
		return ( is_admin() );
	}

	/**
	 * @ignore
	 * @return boolean
	 */
	final public function enable_scripting()
	{
		return ( is_admin() );
	}

	/**
	 * @return Infinity_Screens_Registry
	 */
	final public function new_registry()
	{
		return new Infinity_Screens_Registry();
	}

	/**
	 * @return Infinity_Exts_Screen_Factory
	 */
	final public function new_factory()
	{
		return new Infinity_Exts_Screen_Factory();
	}

	/**
	 * @return Infinity_Screens_Renderer
	 */
	final public function new_renderer()
	{
		return new Infinity_Screens_Renderer();
	}

}

/**
 * Infinity Theme: screens registry
 *
 * @package Infinity
 * @subpackage screens
 */
class Infinity_Screens_Registry extends Pie_Easy_Screens_Registry
{
	// nothing custom yet
}

/**
 * Infinity Theme: section factory
 *
 * @package Infinity
 * @subpackage exts
 */
class Infinity_Exts_Screen_Factory extends Pie_Easy_Screens_Factory
{
	// nothing custom yet
}

/**
 * Infinity Theme: screens renderer
 *
 * @package Infinity
 * @subpackage screens
 */
class Infinity_Screens_Renderer extends Pie_Easy_Screens_Renderer
{
	// nothing custom yet
}

//
// Helpers
//

/**
 * Initialize screens environment
 */
function infinity_screens_init( $theme = null )
{
	// component policy
	$screens_policy = Infinity_Screens_Policy::instance();

	// enable component
	Pie_Easy_Scheme::instance($theme)->enable_component( $screens_policy );

	do_action( 'infinity_screens_init' );
}

/**
 * Initialize screens screen requirements
 */
function infinity_screens_init_screen()
{
	// init ajax OR screen reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		Infinity_Screens_Policy::instance()->registry()->init_ajax();
		do_action( 'infinity_screens_init_ajax' );
	} else {
		Infinity_Screens_Policy::instance()->registry()->init_screen();
		do_action( 'infinity_screens_init_screen' );
	}
}

?>
