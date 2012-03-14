<?php
/**
 * PIE API: base policy class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

/**
 * Make customizing component implementations easy
 *
 * This object is passed to each component allowing the implementing API to
 * customize the implementation without confusing hooks and such.
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Policy extends Pie_Easy_Base
{
	/**
	 * @var Pie_Easy_Map 
	 */
	static private $instances;

	/**
	 * @var string
	 */
	static protected $calling_class;

	/**
	 * @var Pie_Easy_Registry
	 */
	private $registry;

	/**
	 * @var Pie_Easy_Factory
	 */
	private $factory;
	
	/**
	 * @var Pie_Easy_Renderer
	 */
	private $renderer;

	/**
	 * @internal
	 */
	final protected function __construct()
	{
		// singleton
	}

	/**
	 * Get policy instance
	 *
	 * @param string $class Return policy instance which is instance of this class
	 * @return Pie_Easy_Policy
	 */
	static public function instance( $class = null )
	{
		// init instances map
		if ( !self::$instances instanceof Pie_Easy_Map ) {
			self::$instances = new Pie_Easy_Map();
		}

		// is this a lookup?
		if ( $class ) {
			foreach ( self::$instances as $instance ) {
				if ( $instance instanceof $class ) {
					return $instance;
				}
			}
		} else {
			// create new instance?
			if ( !self::$instances->contains( self::$calling_class ) ) {
				self::$instances->add( self::$calling_class, new self::$calling_class() );
			}
			// return it
			return self::$instances->item_at( self::$calling_class );
		}
	}

	/**
	 * Return array of all policy instances
	 * 
	 * @return array 
	 */
	final static public function all()
	{
		$all = array();
		$options_class = null;
		$options_policy = null;

		foreach( self::$instances as $class => $policy ) {
			if ( $policy instanceof Pie_Easy_Options_Policy ) {
				$options_class = $class;
				$options_policy = $policy;
			} else {
				$all[$class] = $policy;
			}
		}
		
		if ( $options_class ) {
			$all[ $options_class ] = $options_policy;
		}

		return $all;
	}

	/**
	 * @return Pie_Easy_Features_Policy
	 */
	final static public function features()
	{
		return self::instance( 'Pie_Easy_Features_Policy' );
	}

	/**
	 * @return Pie_Easy_Widgets_Policy
	 */
	final static public function widgets()
	{
		return self::instance( 'Pie_Easy_Widgets_Policy' );
	}

	/**
	 * @return Pie_Easy_Options_Policy
	 */
	final static public function options()
	{
		return self::instance( 'Pie_Easy_Options_Policy' );
	}

	/**
	 * @return Pie_Easy_Screens_Policy
	 */
	final static public function screens()
	{
		return self::instance( 'Pie_Easy_Screens_Policy' );
	}

	/**
	 * @return Pie_Easy_Sections_Policy
	 */
	final static public function sections()
	{
		return self::instance( 'Pie_Easy_Sections_Policy' );
	}

	/**
	 * @return Pie_Easy_Shortcodes_Policy
	 */
	final static public function shortcodes()
	{
		return self::instance( 'Pie_Easy_Shortcodes_Policy' );
	}

	/**
	 * Return the name of the implementing API
	 *
	 * If you are rolling your own parent theme, this should probably return the
	 * name of the theme you are creating.
	 *
	 * @return string
	 */
	abstract public function get_api_slug();

	/**
	 * Return the name of the policy handle
	 *
	 * @return string
	 */
	abstract public function get_handle();

	/**
	 * Return a new instance of a component registry
	 *
	 * @return Pie_Easy_Registry
	 */
	abstract public function new_registry();
	
	/**
	 * Return a new instance of a component factory
	 *
	 * @return Pie_Easy_Factory
	 */
	abstract public function new_factory();

	/**
	 * Return a new instance of a component renderer
	 *
	 * @return Pie_Easy_Renderer
	 */
	abstract public function new_renderer();

	/**
	 * Return the registry instance
	 *
	 * @return Pie_Easy_Registry
	 */
	final public function registry()
	{
		// handle empty registry
		if ( !$this->registry instanceof Pie_Easy_Registry ) {
			$this->registry = $this->new_registry();
			$this->registry->policy( $this );
		}
		return $this->registry;
	}
	
	/**
	 * Return the factory instance
	 *
	 * @return Pie_Easy_Sections_Factory
	 */
	final public function factory()
	{
		if ( !$this->factory instanceof Pie_Easy_Factory ) {
			$this->factory = $this->new_factory();
			$this->factory->policy( $this );
		}
		return $this->factory;
	}

	/**
	 * Return the renderer instance
	 *
	 * @return Pie_Easy_Renderer
	 */
	final public function renderer()
	{
		if ( !$this->renderer instanceof Pie_Easy_Renderer ) {
			$this->renderer = $this->new_renderer();
			$this->renderer->policy( $this );
		}
		return $this->renderer;
	}
}

?>
