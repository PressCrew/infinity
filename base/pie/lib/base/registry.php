<?php
/**
 * PIE API: base registry
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load(
	'base/componentable',
	'init/configuration',
	'utils/export'
);

/**
 * Make keeping track of concrete components
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Registry extends Pie_Easy_Componentable
{
	/**
	 * Name of the default component to use when none configured
	 */
	const DEFAULT_COMPONENT_TYPE = 'default';
	
	/**
	 * Sub option delimeter
	 */
	const SUB_OPTION_DELIM = '.';

	/**
	 * Name of the theme currently being loaded
	 *
	 * @var string
	 */
	static private $theme_scope;

	/**
	 * Registered components map
	 *
	 * @var Pie_Easy_Map
	 */
	private $components;

	/**
	 * Singleton constructor
	 * 
	 * @internal
	 */
	public function __construct()
	{
		// init local collections
		$this->components = new Pie_Easy_Map();
	}

	/**
	 * Init ajax requirements
	 */
	public function init_ajax()
	{
		// init ajax for each registered component
		foreach ( $this->get_all() as $component ) {
			$component->init_ajax();
		}
	}

	/**
	 * Init screen dependencies for all applicable components to be rendered
	 */
	public function init_screen()
	{
		add_action( 'pie_easy_init_styles', array($this, 'init_styles') );
		add_action( 'pie_easy_init_scripts', array($this, 'init_scripts') );
		
		// init screen for each registered component
		foreach ( $this->get_all() as $component ) {
			$component->init_screen();
		}
	}

	/**
	 * Enqueue required styles
	 */
	public function init_styles()
	{
		foreach ( $this->get_all() as $component ) {
			$component->init_styles();
		}
	}

	/**
	 * Enqueue required scripts
	 */
	public function init_scripts()
	{
		// init scripts for each registered component
		foreach ( $this->get_all() as $component ) {
			$component->init_scripts();
		}
	}

	/**
	 * Template method to allow localization of scripts
	 */
	protected function localize_script() {}

	/**
	 * Register a component
	 *
	 * @param Pie_Easy_Component $component
	 * @return boolean
	 */
	final protected function register( Pie_Easy_Component $component )
	{
		// has the component already been registered?
		if ( !$this->has( $component->name ) ) {
			// register it
			$this->components->add( $component->name, $component );
		}

		return true;
	}

	/**
	 * Returns true if a component has been registered
	 *
	 * @param string $name
	 * @return boolean
	 */
	final public function has( $name )
	{
		return $this->components->contains( $name );
	}

	/**
	 * Return a registered component object by name
	 *
	 * @param string $name
	 * @return Pie_Easy_Component
	 */
	final public function get( $name )
	{
		// check registry
		if ( $this->has( $name ) ) {
			// from top of stack
			return $this->components->item_at( $name );
		}

		// didn't find it
		throw new Exception( sprintf( 'Unable to get component "%s": not registered', $name ) );
	}

	/**
	 * Return all registered components as an array
	 *
	 * @param boolean $include_ignored Set to true to also return components which have ignore set to true
	 * @return array
	 */
	final public function get_all( $include_ignored = false )
	{
		// components to return
		$components = array();

		// loop through and compare names
		foreach ( $this->components as $component ) {
			// include ignored?
			if ( !$include_ignored ) {
				// check ignore toggle
				if ( $component->ignore ) {
					// component is explicitly ignored
					continue;
				} elseif ( $component->parent && $component->get_parent()->ignore ) {
					// component parent is ignored, applies to this child
					continue;
				}
			}
			// add to array
			$components[] = $component;
		}

		// return them
		return $components;
	}

	/**
	 * Return all registered child components of a component
	 *
	 * This adheres to parent settings in the component ini file
	 *
	 * @param Pie_Easy_Component $component The component object whose children you want to get
	 * @return array
	 */
	public function get_children( Pie_Easy_Component $component )
	{
		// the components that will be returned
		$components = array();

		// find all registered component where parent is the target component
		foreach ( $this->get_all() as $component_i ) {
			if ( $component->is_parent_of( $component_i ) ) {
				$components[] = $component_i;
			}
		}

		return $components;
	}

	/**
	 * Get components that should behave as a root component
	 *
	 * This method mostly exists as a helper to use when rendering menus
	 *
	 * @param array $component_names An array of component names to include, defaults to all
	 * @return array
	 */
	public function get_roots( $component_names = array() )
	{
		// components to be returned
		$components = array();

		// loop through all registered components
		foreach ( $this->get_all() as $component ) {
			// filter on component names
			if ( empty( $component_names ) || in_array( $component->name, $component_names, true ) ) {
				$components[] = $component;
			}
		}

		// don't return components who have a parent in the result
		foreach( $components as $key => $component_i ) {
			if ( isset( $component_i->parent ) ) {
				unset( $components[$key] );
			}
		}

		return $components;
	}

	/**
	 * Load directives from an ini file
	 *
	 * @uses parse_ini_file()
	 * @param string $filename Absolute path to the component ini file to parse
	 * @param string $theme The theme to assign the parsed directives to
	 * @return boolean
	 */
	final public function load_config_file( $filename, $theme )
	{
		// set the current theme being loaded
		self::$theme_scope = $theme;

		// try to parse the file
		$result = $this->load_config_sections( parse_ini_file( $filename, true ) );

		// remove theme scope
		self::$theme_scope = null;

		return $result;
	}

	/**
	 * Load components into registry from an array (of parsed ini sections)
	 *
	 * @param array $sections_array
	 * @return boolean
	 */
	private function load_config_sections( $sections_array )
	{
		// an array means successful parse
		if ( is_array( $sections_array ) ) {
			// loop through each section
			foreach ( $sections_array as $section_name => $section_array ) {
				// load one section
				$this->load_config_section( $section_name, $section_array );
			}
			// all done
			return true;
		}

		return false;
	}

	/**
	 * Load one component into registry from a single parsed ini section (array)
	 *
	 * @param string $section_name
	 * @param array $section_array
	 * @return boolean
	 */
	private function load_config_section( $section_name, $section_array )
	{
		// set default type if necessary
		if ( empty( $section_array['type'] ) ) {
			$section_array['type'] = self::DEFAULT_COMPONENT_TYPE;
		}

		// convert config array to map
		$config = new Pie_Easy_Init_Config( $section_array );

		// options component enabled?
		if ( $this->policy()->options() instanceof Pie_Easy_Policy ) {
			// is it a sub option?
			if ( $this->policy()->options()->registry()->load_feature_option( $section_name, $config ) ) {
				// yes, skip standard loading
				return true;
			}
		}

		return $this->load_config_map( $section_name, $config );
	}

	/**
	 * Load one component given its name and config map
	 *
	 * @param string $name
	 * @param Pie_Easy_Init_Config $config
	 * @return boolean
	 */
	protected function load_config_map( $name, Pie_Easy_Init_Config $config )
	{
		// use factory to create/get component
		$component =
			$this->policy()->factory()->create(
				$name,
				$config
			);

		// configure it
		$component->configure( $config );

		// register component
		return $this->register( $component );
	}

	/**
	 * Return the current theme scope
	 *
	 * @return string
	 */
	static public function theme_scope()
	{
		// if the theme scope is needed, then its assumed that some kind of
		// loading or component creation is happening. since theme scope
		// is required for these procedures, throw an error
		// if theme scope is null
		if ( empty( self::$theme_scope ) ) {
			throw new Exception( 'The theme scope is empty, something went horribly wrong!' );
		}

		// return the current theme scope
		return self::$theme_scope;
	}
}

?>
