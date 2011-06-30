<?php
/**
 * PIE API: base registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */


Pie_Easy_Loader::load( 'base/componentable', 'collections', 'utils/export' );

/**
 * Make keeping track of concrete components
 *
 * @package PIE
 * @subpackage base
 */
abstract class Pie_Easy_Registry extends Pie_Easy_Componentable
{
	/**
	 * Name of parameter which passes back blog id
	 */
	const PARAM_BLOG_ID = 'pie_easy_options_blog_id';

	/**
	 * Name of parameter which passes back blog theme
	 */
	const PARAM_BLOG_THEME = 'pie_easy_options_blog_theme';

	/**
	 * Name of the theme currently being loaded
	 *
	 * @var string
	 */
	protected $loading_theme;

	/**
	 * Blog id when screen was initialized
	 *
	 * @var integer
	 */
	protected $screen_blog_id;

	/**
	 * Blog theme when screen was initialized
	 *
	 * @var string
	 */
	protected $screen_blog_theme;

	/**
	 * Registered components map
	 *
	 * @var Pie_Easy_Map
	 */
	private $components;

	/**
	 * @var Pie_Easy_Export
	 */
	private $export_css_file;

	/**
	 * Singleton constructor
	 * @ignore
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
		// init ajax for each concrete component
	}

	/**
	 * Init screen dependencies for all applicable components to be rendered
	 */
	public function init_screen()
	{
		global $blog_id;

		$this->screen_blog_id = (integer) $blog_id;
		$this->screen_blog_theme = get_stylesheet();

		add_action( 'pie_easy_init_styles', array($this, 'init_styles') );
		add_action( 'pie_easy_init_scripts', array($this, 'init_scripts') );
	}

	/**
	 * Enqueue required styles
	 */
	public function init_styles() {}

	/**
	 * Enqueue required scripts
	 */
	public function init_scripts() {}

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
		if ( $this->components->contains( $component->name ) ) {

			// get component stack
			$comp_stack = $this->get_stack( $component->name );

			// check if component already registered for this theme
			if ( $comp_stack->contains( $component->theme ) ) {
				throw new Exception( sprintf(
					'The "%s" component has already been registered for the "%s" theme',
					$component->name, $component->theme ) );
			}

		} else {
			$comp_stack = new Pie_Easy_Stack();
			$this->components->add( $component->name, $comp_stack );
		};

		// set policy
		$component->policy( $this->policy() );

		// register it
		$comp_stack->push( $component );

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
			return $this->get_stack($name)->peek();
		}

		// didn't find it
		throw new Exception( sprintf( 'Unable to get component "%s": not registered', $name ) );
	}

	/**
	 * Return all registered components as an array
	 *
	 * @return array
	 */
	final public function get_all( $include_ignored = false )
	{
		// components to return
		$components = array();

		// loop through and compare names
		foreach ( $this->components as $component_stack ) {
			// current component is on top of stack
			$component = $component_stack->peek();
			// include ignored?
			if ( ($component->ignore) && (!$include_ignored) ) {
				// next!
				continue;
			} else {
				// add to array
				$components[] = $component;
			}
		}

		// return them
		return $components;
	}

	/**
	 * Return stack for the given component name
	 *
	 * @param string $name
	 * @return Pie_Easy_Stack
	 */
	final protected function get_stack( $name )
	{
		return $this->components->item_at( $name );
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
			foreach( $components as $component_ii ) {
				if ( $component_ii->is_parent_of( $component_i ) ) {
					unset( $components[$key] );
				}
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
		$this->loading_theme = $theme;

		// try to parse the file
		return $this->load_config_array( parse_ini_file( $filename, true ) );
	}

	/**
	 * Load components into registry from an array (of parsed ini sections)
	 *
	 * @param array $ini_array
	 * @return boolean
	 */
	private function load_config_array( $ini_array )
	{
		// an array means successful parse
		if ( is_array( $ini_array ) ) {
			// loop through each directive
			foreach ( $ini_array as $s_name => $s_config ) {
				// get component
				$component = $this->load_config_single( $s_name, $s_config );
				// valid component?
				if ( $component instanceof Pie_Easy_Component ) {
					// set component vars and register it
					$this->set_component_vars( $component, $s_config );
					$this->register( $component );
				}
			}
			// all done
			return true;
		}

		return false;
	}

	/**
	 * Load a single component into the registry (one parsed ini section)
	 *
	 * @param string $name
	 * @param array $config
	 * @return boolean
	 */
	abstract protected function load_config_single( $name, $config );

	/**
	 * Set custom directives for component (pass thru vars)
	 *
	 * @param Pie_Easy_Component $component
	 * @param array $config_array
	 */
	private function set_component_vars( Pie_Easy_Component $component, $config_array )
	{
		// loop through config
		foreach( $config_array as $directive => $value ) {
			// try to set it
			$component->set_directive_var( $directive, $value );
		}
	}

	/**
	 * Export CSS markup from all registered component
	 * that implement the styleable interface
	 *
	 * @return string
	 */
	public function export_css()
	{
		// css to return
		$css = '';

		// loop through for import rules
		foreach ( $this->get_all() as $component ) {
			// component must be supported
			if ( $component->supported() ) {
				// get import rules
				$css .= $component->import_css();
			}
		}

		// prettyfication
		$css .= "\n";

		// loop through for inline rules
		foreach ( $this->get_all() as $component ) {
			// component must be supported
			if ( $component->supported() ) {
				// get inline rules
				$css .= $component->export_css();
			}
		}

		return $css;
	}

	/**
	 * Return dynamic css object
	 *
	 * @return Pie_Easy_Export
	 */
	public function export_css_file()
	{
		if ( !$this->export_css_file instanceof Pie_Easy_Export ) {
			$this->export_css_file =
				new Pie_Easy_Export(
					$this->policy()->get_handle() . '.css',
					array( $this, 'export_css' )
				);
		}

		return $this->export_css_file;
	}

}

?>
