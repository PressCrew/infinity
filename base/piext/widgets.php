<?php
/**
 * Infinity Theme: widgets classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-components
 * @subpackage widgets
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/widgets' );

/**
 * Infinity Theme: widgets policy
 *
 * @package Infinity-components
 * @subpackage widgets
 */
class Infinity_Widgets_Policy extends Pie_Easy_Widgets_Policy
{
	/**
	 * @return Pie_Easy_Widgets_Policy
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
		return ( is_admin() );
	}

	/**
	 */
	final public function enable_scripting()
	{
		return ( is_admin() );
	}
	
	/**
	 * @return Infinity_Widgets_Registry
	 */
	final public function new_registry()
	{
		return new Infinity_Widgets_Registry();
	}

	/**
	 * @return Infinity_Exts_Widget_Factory
	 */
	final public function new_factory()
	{
		return new Infinity_Exts_Widget_Factory();
	}

	/**
	 * @return Infinity_Widgets_Renderer
	 */
	final public function new_renderer()
	{
		return new Infinity_Widgets_Renderer();
	}

}

/**
 * Infinity Theme: widgets registry
 *
 * @package Infinity-components
 * @subpackage widgets
 */
class Infinity_Widgets_Registry extends Pie_Easy_Widgets_Registry
{
	// nothing custom yet
}

/**
 * Infinity Theme: section factory
 *
 * @package Infinity-extensions
 * @subpackage widgets
 */
class Infinity_Exts_Widget_Factory extends Pie_Easy_Widgets_Factory
{
	// nothing custom yet
}

/**
 * Infinity Theme: widgets renderer
 *
 * @package Infinity-components
 * @subpackage widgets
 */
class Infinity_Widgets_Renderer extends Pie_Easy_Widgets_Renderer
{
	// nothing custom yet
}

//
// Helpers
//

/**
 * Display a widget
 *
 * @package Infinity-components
 * @subpackage widgets
 * @param string $widget_name
 * @param boolean $output
 * @return string|false
 */
function infinity_widget( $widget_name, $output = true )
{
	return Infinity_Widgets_Policy::instance()->registry()->get($widget_name)->render( $output );
}

/**
 * Initialize widgets environment
 *
 * @package Infinity-components
 * @subpackage widgets
 */
function infinity_widgets_init()
{
	// component policy
	$widgets_policy = Infinity_Widgets_Policy::instance();

	// enable component
	Pie_Easy_Scheme::instance()->enable_component( $widgets_policy );

	do_action( 'infinity_widgets_init' );
}

/**
 * Initialize widgets screen requirements
 *
 * @package Infinity-components
 * @subpackage widgets
 */
function infinity_widgets_init_screen()
{
	// init ajax OR widget reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		Infinity_Widgets_Policy::instance()->registry()->init_ajax();
		do_action( 'infinity_widgets_init_ajax' );
	} else {
		Infinity_Widgets_Policy::instance()->registry()->init_screen();
		do_action( 'infinity_widgets_init_widget' );
	}
}

?>
