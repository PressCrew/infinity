<?php
/**
 * ICE API: screens policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage screens
 * @since 1.0
 */

ICE_Loader::load( 'base/policy' );

/**
 * Make customizing screen implementations easy
 *
 * This object is passed to each screen allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package ICE-components
 * @subpackage screens
 */
class ICE_Screen_Policy extends ICE_Policy
{
	/**
	 * @return ICE_Screen_Policy
	 */
	static public function instance()
	{
		self::$calling_class = __CLASS__;
		return parent::instance();
	}

	/**
	 * @return string
	 */
	public function get_handle( $plural = true )
	{
		return ( $plural ) ? 'screens' : 'screen';
	}

	/**
	 * @return ICE_Screen_Registry
	 */
	final public function new_registry()
	{
		return new ICE_Screen_Registry();
	}

	/**
	 * @return ICE_Screen_Factory
	 */
	final public function new_factory()
	{
		return new ICE_Screen_Factory();
	}

	/**
	 * @return ICE_Screen_Renderer
	 */
	final public function new_renderer()
	{
		return new ICE_Screen_Renderer();
	}
}
