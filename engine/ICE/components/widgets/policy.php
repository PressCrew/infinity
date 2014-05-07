<?php
/**
 * ICE API: widgets policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage widgets
 * @since 1.0
 */

ICE_Loader::load( 'base/policy' );

/**
 * Make customizing widget implementations easy
 *
 * This object is passed to each widget allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package ICE-components
 * @subpackage widgets
 */
class ICE_Widget_Policy extends ICE_Policy
{
	/**
	 * @return string
	 */
	public function get_handle( $plural = true )
	{
		return ( $plural ) ? 'widgets' : 'widget';
	}

	/**
	 * @return ICE_Widget_Registry
	 */
	final public function new_registry()
	{
		return new ICE_Widget_Registry( $this );
	}

	/**
	 * @return ICE_Widget_Factory
	 */
	final public function new_factory()
	{
		return new ICE_Widget_Factory( $this );
	}

	/**
	 * @return ICE_Widget_Renderer
	 */
	final public function new_renderer()
	{
		return new ICE_Widget_Renderer( $this );
	}
}
