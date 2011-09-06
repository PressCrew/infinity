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
	protected $loading_theme;

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
	 * @var Pie_Easy_Export
	 */
	private $export_js_file;

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
		if ( $this->has( $component->name ) ) {
			// can't register same component name twice
			throw new Exception( sprintf(
				'The "%s" component has already been registered for the "%s" theme',
				$component->name, $component->theme ) );
		}
		
		// register it
		$this->components->add( $component->name, $component );

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
			foreach ( $ini_array as $name => $config ) {
				// convert config array to map
				$conf_map = new Pie_Easy_Map( $config );
				// sub option?
				if ( $this->load_sub_option( $name, $conf_map ) ) {
					// yes, skip standard loading
					continue;
				}
				// get or create component
				if ( $this->has( $name ) ) {
					// get it from registry
					$component = $this->get( $name );
				} else {
					// use factory to create one
					$component =
						$this->policy()->factory()->create(
							$this->loading_theme,
							$name,
							$conf_map->type ? $conf_map->type : self::DEFAULT_COMPONENT_TYPE
						);
					// register component
					$this->register( $component );
				}
				// configure component
				$component->configure( $conf_map, $this->loading_theme );
			}
			// all done
			return true;
		}

		return false;
	}

	/**
	 * Load config as a sub option if syntax of name calls for it
	 *
	 * @param string $name
	 * @param Pie_Easy_Map $conf_map
	 * @return boolean
	 */
	private function load_sub_option( $name, Pie_Easy_Map $conf_map )
	{
		// split for possible sub option syntax
		$parts = explode( self::SUB_OPTION_DELIM, $name );

		// if has exactly two parts it is a sub option
		if ( count($parts) == 2 ) {
			// make sure options component has been enabled
			if ( $this->policy()->options() instanceof Pie_Easy_Policy ) {
				// feature name is the first string
				$feature_name = $parts[0];
				// option name is both strings glued with a hyphen
				$option_name = implode( '-', $parts );
				// get or create component
				if ( $this->policy()->options()->registry()->has( $name ) ) {
					// get it from registry
					$component = $this->get( $name );
				} else {
					// create option using the option component factory
					$component =
						$this->policy()->options()->factory()->create(
							$this->loading_theme,
							$option_name,
							$conf_map->type ? $conf_map->type : self::DEFAULT_COMPONENT_TYPE
						);
					// register option
					$this->policy()->options()->registry()->register( $component );
				}
				// automagically set required feature if applicable
				if ( $this instanceof Pie_Easy_Features_Registry ) {
					$conf_map->required_feature = $feature_name;
				}
				// configure component
				$component->configure( $conf_map, $this->loading_theme );
				// all done
				return true;
			} else {
				throw new Exception(
					'Unable to load sub option because options component has not been enabled' );
			}
		}

		return false;
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
		if ( $css ) {
			$css .= "\n";
		}

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

	/**
	 * Export javascript code from all registered component
	 * that implement the scriptable interface
	 *
	 * @return string
	 */
	public function export_script()
	{
		// code to return
		$code = '';

		// loop through for code to import
		foreach ( $this->get_all() as $component ) {
			// component must be supported
			if ( $component->supported() ) {
				// get import rules
				$code .= $component->import_script();
			}
		}

		// prettyfication
		if ( $code ) {
			$code .= "\n";
		}

		// loop through all components
		foreach ( $this->get_all() as $component ) {
			// component must be supported
			if ( $component->supported() ) {
				// get code
				$code .= $component->export_script();
			}
		}

		return $code;
	}
	
	/**
	 * Return dynamic script object
	 *
	 * @return Pie_Easy_Export
	 */
	public function export_js_file()
	{
		if ( !$this->export_js_file instanceof Pie_Easy_Export ) {
			$this->export_js_file =
				new Pie_Easy_Export(
					$this->policy()->get_handle() . '.js',
					array( $this, 'export_script' )
				);
		}

		return $this->export_js_file;
	}

}

?>
