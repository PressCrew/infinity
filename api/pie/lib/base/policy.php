<?php
/**
 * PIE API: base policy class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
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
abstract class Pie_Easy_Policy
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
	 * Map of Pie_Easy_Registry instances
	 *
	 * @var Pie_Easy_Map
	 */
	private $registries;

	/**
	 * @var Pie_Easy_Factory
	 */
	private $factory;
	
	/**
	 * @var Pie_Easy_Renderer
	 */
	private $renderer;

	/**
	 * @ignore
	 */
	private function __construct()
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
	 * Return the registry for a specific point in the theme ancestory
	 *
	 * @param $theme Theme for which to return the registry
	 * @return Pie_Easy_Registry
	 */
	final public function registry( $theme = null )
	{
		// handle empty theme
		if ( empty( $theme ) ) {
			$theme = get_stylesheet();
		}

		// handle empty registry map
		if ( !$this->registries instanceof Pie_Easy_Map ) {
			$this->registries = new Pie_Easy_Map();
		}

		// need new instance?
		if ( !$this->registries->contains( $theme ) ) {
			// push this registry onto the map
			$registry = $this->new_registry();
			$registry->policy( $this );
			$this->registries->add( $theme, $registry );
		}

		return $this->registries->item_at( $theme );
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
			$this->factory()->policy( $this );
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

	/**
	 * Load a component extension which exists in a custom location
	 *
	 * This method will always be tried first, before PIE loads its core component class files
	 *
	 * @param string $ext Name of the extension
	 * @return boolean
	 */
	public function load_ext( $ext )
	{
		// override this class to load component class files which exist outside of PIE's path
		return false;
	}
}

?>
