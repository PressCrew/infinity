<?php
/**
 * ICE API: shortcodes policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage shortcodes
 * @since 1.0
 */

ICE_Loader::load( 'base/policy' );

/**
 * Make customizing shortcode implementations easy
 *
 * This object is passed to each shortcode allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package ICE-components
 * @subpackage shortcodes
 */
class ICE_Shortcode_Policy extends ICE_Policy
{	
	/**
	 * @return string
	 */
	public function get_handle( $plural = true )
	{
		return ( $plural ) ? 'shortcodes' : 'shortcode';
	}

	/**
	 * @return ICE_Shortcode_Registry
	 */
	final public function new_registry()
	{
		return new ICE_Shortcode_Registry( $this );
	}

	/**
	 * @return ICE_Extensions
	 */
	final public function new_extensions()
	{
		return new ICE_Extensions( $this );
	}

	/**
	 * @return ICE_Shortcode_Renderer
	 */
	final public function new_renderer()
	{
		return new ICE_Shortcode_Renderer( $this );
	}
}
