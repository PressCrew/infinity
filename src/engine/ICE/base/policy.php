<?php
/**
 * ICE API: base policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage base
 * @since 1.0
 */

ICE_Loader::load_lib( 'base/extensions' );

/**
 * Make customizing component implementations easy
 *
 * This object is passed to each component allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package ICE
 * @subpackage base
 */
abstract class ICE_Policy extends ICE_Base
{
	/**
	 * @todo Provide public method for overriding class settings.
	 * @var array
	 */
	static private $classes = array(
		'option' => 'ICE_Option_Policy',
		'feature' => 'ICE_Feature_Policy',
		'section' => 'ICE_Section_Policy',
		'screen' => 'ICE_Screen_Policy',
		'widget' => 'ICE_Widget_Policy'
	);

	/**
	 * @var array
	 */
	static private $instances = array();

	/**
	 * @var ICE_Extensions
	 */
	private $extensions;

	/**
	 * @var ICE_Registry
	 */
	private $registry;
	
	/**
	 * @var ICE_Renderer
	 */
	private $renderer;

	/**
	 * Get policy instance for given type.
	 *
	 * @param string $type Return policy instance which is this type of class.
	 * @return ICE_Policy
	 */
	static public function instance( $type )
	{
		// create new instance?
		if ( false === isset( self::$instances[ $type ] ) ) {
			// yep, get class name
			$class_name = self::$classes[ $type ];
			// create it
			self::$instances[ $type ] = new $class_name();
		}
		// return it
		return self::$instances[ $type ];
	}

	/**
	 * Return array of all policy instances
	 * 
	 * @return array 
	 */
	final static public function all()
	{
		return self::$instances;
	}

	/**
	 * @return ICE_Feature_Policy
	 */
	final static public function features()
	{
		return self::instance( 'feature' );
	}

	/**
	 * @return ICE_Option_Policy
	 */
	final static public function options()
	{
		return self::instance( 'option' );
	}

	/**
	 * @return ICE_Screen_Policy
	 */
	final static public function screens()
	{
		return self::instance( 'screen' );
	}

	/**
	 * @return ICE_Section_Policy
	 */
	final static public function sections()
	{
		return self::instance( 'section' );
	}

	/**
	 * @return ICE_Widget_Policy
	 */
	final static public function widgets()
	{
		return self::instance( 'widget' );
	}

	/**
	 * Return the name of the policy handle
	 *
	 * @param boolean $plural set to false to return singular form of handle
	 * @return string
	 */
	abstract public function get_handle( $plural = true );

	/**
	 * Return a new instance of a component registry.
	 *
	 * @return ICE_Registry
	 */
	protected function new_registry()
	{
		return new ICE_Registry( $this );
	}
	
	/**
	 * Return a new instance of an extensions manager.
	 *
	 * @return ICE_Extensions
	 */
	protected function new_extensions()
	{
		return new ICE_Extensions( $this );
	}

	/**
	 * Return a new instance of a component renderer
	 *
	 * @return ICE_Renderer
	 */
	protected function new_renderer()
	{
		return new ICE_Renderer( $this );
	}

	/**
	 * Return extensions registry instance.
	 *
	 * @return ICE_Extensions
	 */
	final public function extensions()
	{
		if ( !$this->extensions instanceof ICE_Extensions ) {
			// create new instance
			$this->extensions = $this->new_extensions();
			// register bundled extensions
			ice_loader_safe_require(
				ICE_EXT_PATH . '/' .
				$this->get_handle( true ) . '/' .
				'register.php'
			);
		}
		return $this->extensions;
	}

	/**
	 * Return the registry instance
	 *
	 * @return ICE_Registry
	 */
	final public function registry()
	{
		// handle empty registry
		if ( !$this->registry instanceof ICE_Registry ) {
			$this->registry = $this->new_registry();
		}
		return $this->registry;
	}

	/**
	 * Return the renderer instance
	 *
	 * @return ICE_Renderer
	 */
	final public function renderer()
	{
		if ( !$this->renderer instanceof ICE_Renderer ) {
			$this->renderer = $this->new_renderer();
		}
		return $this->renderer;
	}

	/**
	 * This template method is called when this scheme is finalized.
	 */
	public function finalize()
	{
		// finalize the registry
		$this->registry()->finalize();
	}
}
