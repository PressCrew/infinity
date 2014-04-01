<?php
/**
 * ICE API: options policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load( 'base/policy' );

/**
 * Make customizing options implementations easy
 *
 * This object is passed to each option allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package ICE-components
 * @subpackage options
 */
class ICE_Option_Policy extends ICE_Policy
{
	/**
	 * The body class for option overrides
	 * 
	 * @var string 
	 */
	private $body_class = 'theme-option';

	/**
	 * @return ICE_Option_Policy
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
		return ( $plural ) ? 'options' : 'option';
	}

	/**
	 * @return ICE_Option_Registry
	 */
	final public function new_registry()
	{
		return new ICE_Option_Registry();
	}

	/**
	 * @return ICE_Option_Factory
	 */
	final public function new_factory()
	{
		return new ICE_Option_Factory();
	}

	/**
	 * @return ICE_Option_Renderer
	 */
	final public function new_renderer()
	{
		return new ICE_Option_Renderer();
	}

	/**
	 */
	public function finalize()
	{
		// run parent first
		if ( true === parent::finalize() ) {
			// hook in to body class filter
			add_filter( 'body_class', array( $this, 'add_body_class' ) );
		}
	}

	/**
	 * Return the body class
	 * 
	 * @return string 
	 */
	public function get_body_class()
	{
		return $this->body_class;
	}

	/**
	 * Append body class to given array
	 *
	 * @param array $classes
	 * @return array
	 */
	public function add_body_class( $classes )
	{
		// append to classes array
		$classes[] = $this->body_class;
		// all done
		return $classes;
	}
}
