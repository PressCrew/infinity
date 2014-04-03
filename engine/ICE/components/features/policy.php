<?php
/**
 * ICE API: features policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'base/policy' );

/**
 * Make customizing feature implementations easy
 *
 * This object is passed to each feature allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package ICE-components
 * @subpackage features
 */
class ICE_Feature_Policy extends ICE_Policy
{
	/**
	 * @return string
	 */
	public function get_handle( $plural = true )
	{
		return ( $plural ) ? 'features' : 'feature';
	}

	/**
	 * @return ICE_Feature_Registry
	 */
	final public function new_registry()
	{
		return new ICE_Feature_Registry();
	}

	/**
	 * @return ICE_Feature_Factory
	 */
	final public function new_factory()
	{
		return new ICE_Feature_Factory();
	}

	/**
	 * @return ICE_Feature_Renderer
	 */
	final public function new_renderer()
	{
		return new ICE_Feature_Renderer();
	}
}
