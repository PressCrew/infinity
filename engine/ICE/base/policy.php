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
		'shortcode' => 'ICE_Shortcode_Policy',
		'widget' => 'ICE_Widget_Policy'
	);

	/**
	 * @var array
	 */
	static private $instances = array(
		'option' => null,
		'feature' => null,
		'section' => null,
		'screen' => null,
		'shortcode' => null,
		'widget' => null
	);

	/**
	 * @var ICE_Extensions
	 */
	private $extensions;

	/**
	 * @var ICE_Registry
	 */
	private $registry;

	/**
	 * @var ICE_Factory
	 */
	private $factory;
	
	/**
	 * @var ICE_Renderer
	 */
	private $renderer;

	/**
	 * Singleton constructor.
	 *
	 * @internal
	 */
	final protected function __construct()
	{
		// set up extensions registry
		$this->extensions = new ICE_Extensions( $this );

		// register bundled extensions
		$this->extensions->register_file(
			ICE_EXT_PATH . '/' .
			$this->get_handle( true ) . '/' .
			'register.php'
		);
	}

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
	 * @return ICE_Shortcode_Policy
	 */
	final static public function shortcodes()
	{
		return self::instance( 'shortcode' );
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
	 * Return a new instance of a component registry
	 *
	 * @return ICE_Registry
	 */
	abstract public function new_registry();
	
	/**
	 * Return a new instance of a component factory
	 *
	 * @return ICE_Factory
	 */
	abstract public function new_factory();

	/**
	 * Return a new instance of a component renderer
	 *
	 * @return ICE_Renderer
	 */
	abstract public function new_renderer();

	/**
	 * Return extensions registry instance.
	 *
	 * @return ICE_Extensions
	 */
	final public function extensions()
	{
		// return extensions registry
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
			$this->registry->policy( $this );
		}
		return $this->registry;
	}
	
	/**
	 * Return the factory instance
	 *
	 * @return ICE_Section_Factory
	 */
	final public function factory()
	{
		if ( !$this->factory instanceof ICE_Factory ) {
			$this->factory = $this->new_factory();
			$this->factory->policy( $this );
		}
		return $this->factory;
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
			$this->renderer->policy( $this );
		}
		return $this->renderer;
	}

	/**
	 * This template method is called when this policy is enabled
	 *
	 * @return boolean
	 */
	public function finalize()
	{
		return true;
	}
}
