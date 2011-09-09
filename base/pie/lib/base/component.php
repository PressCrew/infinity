<?php
/**
 * PIE API: base component class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load(
	'base/componentable',
	'base/style',
	'base/styleable',
	'base/script',
	'base/scriptable',
	'init/directive',
	'utils/files'
);

/**
 * Make content components easy
 *
 * @package PIE
 * @subpackage base
 * @property-read string $theme The theme that created this concrete component
 * @property-read string $name The concrete component name
 * @property-read string $parent The parent component (slug)
 * @property-read string $title The concrete component title
 * @property-read string $description The concrete component description
 * @property-read string $class The CSS class to apply to the component's container
 * @property-read boolean|string $documentation true/false to enable/disable, string for manual page name
 * @property-read array $capabilities Required capabilities, can only be appended
 * @property-read string $required_feature Feature required for this component to run/display
 * @property-read boolean $ignore Whether or not this component should be ignored
 * @property-read string $template Relative path to component template file
 */
abstract class Pie_Easy_Component
	extends Pie_Easy_Componentable
		implements Pie_Easy_Styleable, Pie_Easy_Scriptable
{
	/**
	 * Name of the default section
	 */
	const DEFAULT_SECTION = 'default';

	/**
	 * Name of the default templates subdir
	 */
	const DEFAULT_TEMPLATE_DIR = 'templates';
	
	/**
	 * The theme that created this concrete component
	 *
	 * @var string
	 */
	private $__theme__;

	/**
	 * Name of the concrete component
	 *
	 * @var string
	 */
	private $__name__;

	/**
	 * The parent component
	 * 
	 * @var string
	 */
	private $__parent__;
	
	/**
	 * Component directives registry
	 *
	 * @var Pie_Easy_Init_Directive_Registry
	 */
	private $__directives__;

	/**
	 * Component's styling if applicable
	 *
	 * @var Pie_Easy_Style
	 */
	private $__style__;

	/**
	 * Component's scripting if applicable
	 *
	 * @var Pie_Easy_Script
	 */
	private $__script__;

	/**
	 * @param string $theme The theme that created this option
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 */
	public function __construct( $theme, $name )
	{
		// set basic properties
		$this->__theme__ = $theme;
		$this->set_name($name);

		// init directives registry
		$this->__directives__ = new Pie_Easy_Init_Directive_Registry();

		// run init template method
		$this->init();
	}

	/**
	 * WARNING! This magic method creates a black hole for properties.
	 *
	 * Accessing a property which does not exist will always return null!
	 *
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'theme':
				return $this->__theme__;
			case 'name':
				return $this->__name__;
			case 'parent':
				return $this->__parent__;
			default:
				if ( $this->directives()->has($name) ) {
					return $this->directives()->get($name)->value;
				} else {
					return null;
				}
		}
	}

	/**
	 * Return directives registry
	 *
	 * @return Pie_Easy_Init_Directive_Registry
	 */
	final protected function directives()
	{
		return $this->__directives__;
	}

	/**
	 * Return one directive
	 *
	 * @param string $name
	 * @return mixed
	 */
	final protected function directive( $name )
	{
		return $this->directives()->get($name);
	}
	
	/**
	 * Return style object
	 *
	 * @return Pie_Easy_Style
	 */
	public function style()
	{
		if ( !$this->__style__ instanceof Pie_Easy_Style ) {
			$this->__style__ = new Pie_Easy_Style();
		}

		return $this->__style__;
	}

	/**
	 * Return script object
	 *
	 * @return Pie_Easy_Script
	 */
	public function script()
	{
		if ( !$this->__script__ instanceof Pie_Easy_Script ) {
			$this->__script__ = new Pie_Easy_Script();
		}

		return $this->__script__;
	}

	/**
	 * Execute style import
	 *
	 * @return string
	 */
	public function import_css()
	{
		return $this->style()->import();
	}

	/**
	 * Execute script import
	 *
	 * @return string
	 */
	public function import_script()
	{
		return $this->script()->import();
	}

	/**
	 * Execute style exporter
	 *
	 * @return string
	 */
	public function export_css()
	{
		return $this->style()->export();
	}

	/**
	 * Execute script exporter
	 *
	 * @return string
	 */
	public function export_script()
	{
		return $this->script()->export();
	}

	/**
	 * Configure this component from an array of values
	 *
	 * @param Pie_Easy_Map $conf_map
	 * @param string $theme
	 */
	public function configure( $conf_map, $theme )
	{
		// parent
		if ( $conf_map->parent ) {
			if ( $this->__name__ != $conf_map->parent ) {
				$this->__parent__ = trim( $conf_map->parent );
			} else {
				throw new Exception( sprintf( 'The component "%s" cannot be a parent of itself', $this->__name__ ) );
			}
		}
		
		// title
		if ( isset( $conf_map->title ) ) {
			$this->directives()->set( $theme, 'title', $conf_map->title );
		} elseif ( !$this->directives()->has( 'title' ) ) {
			throw new Exception( 'The "title" directive is required' );
		}
		
		// desc
		if ( isset( $conf_map->description ) ) {
			$this->directives()->set( $theme, 'description', $conf_map->description );
		}

		// documentation
		if ( isset( $conf_map->documentation ) ) {
			$this->directives()->set( $theme, 'documentation', $conf_map->documentation );
		}

		// set stylesheet
		if ( isset( $conf_map->style ) ) {
			// no deps by default
			$deps = array();
			// any deps?
			if ( isset( $conf_map->style_depends ) ) {
				$deps = explode( ',', $conf_map->style_depends );
			}
			// set the stylesheet
			$this->style()->add_file( $conf_map->style, $deps );
		}

		// set script
		if ( isset( $conf_map->script ) ) {
			// no deps by default
			$deps = array();
			// any deps?
			if ( isset( $conf_map['script_depends'] ) ) {
				$deps = explode( ',', $conf_map['script_depends'] );
			}
			// set the script
			$this->script()->add_file( $conf_map->script, $deps );
		}

		// set template
		if ( isset( $conf_map->template ) ) {
			$this->directives()->set( $theme, 'template', $conf_map->template );
		}

		// css class
		if ( isset( $conf_map->class ) ) {
			$this->directives()->set( $theme, 'class', $conf_map->class );
		}

		// capabilities
		if ( isset( $conf_map->capabilities ) ) {
			$this->add_capabilities( $conf_map->capabilities );
		}

		// required feature
		if ( isset( $conf_map->required_feature ) ) {
			$this->directives()->set( $theme, 'required_feature', $conf_map->required_feature );
		}

		// set ignore
		if ( isset( $conf_map->ignore ) ) {
			$this->directives()->set( $theme, 'ignore', (boolean) $conf_map->ignore );
		}
	}

	/**
	 * Set additional capabilities which are required for this option to show
	 *
	 * @todo needs a lot of testing
	 * @param string $string A comma separated list of capabilities
	 */
	final public function add_capabilities( $string )
	{
		// split at comma
		$caps = explode( ',', $string );

		// trim and set each
		foreach ( $caps as $cap ) {
			$cap_trimmed = trim( $cap );
			$capabilities[$cap_trimmed] = $cap_trimmed;
		}

		if ( $this->directives()->has('capabilities') ) {
			$theme_map = $this->directives()->get_map( 'capabilities' );
			$theme_caps = $theme_map->item_at($this->__theme__)->value;
			$theme_caps->merge_with( $capabilities );
		} else {
			$this->directives()->set( $this->__theme__, 'capabilities', $capabilities );
		}
	}

	/**
	 * Check that current user has all required capabilities to view/edit this option
	 *
	 * @return boolean
	 */
	final public function check_caps()
	{
		if ( !empty( $this->capabilities ) ) {
			foreach ( $this->capabilities as $cap ) {
				if ( !current_user_can( $cap ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * This template method is called at the end of the constructor
	 */
	protected function init()
	{
		// override this method to initialize special PHP handling for a component
	}

	/**
	 * This template method is called if the current request is AJAX
	 */
	public function init_ajax()
	{
		// override this method to initialize special AJAX handling for an option
	}

	/**
	 * This template method is called on the option renderer init_screen method
	 */
	public function init_screen()
	{
		// override this method to initialize special screen handling for an option
	}

	/**
	 * This template method is called "just in time" to enqueue styles
	 */
	public function init_styles()
	{
		// override this method to initialize special style handling for a component
	}

	/**
	 * This template method is called "just in time" to enqueue scripts
	 */
	public function init_scripts()
	{
		// override this method to initialize special script handling for a component
	}

	/**
	 * Check that component is supported, varies by component
	 *
	 * @return boolean
	 */
	public function supported()
	{
		return $this->check_caps();
	}

	/**
	 * Set the name
	 *
	 * @param string $name
	 */
	private function set_name( $name )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+((_|-)[a-z0-9]+)*$/', $name ) ) {
			$this->__name__ = $name;
			return true;
		} else {
			throw new Exception( sprintf( 'Option name "%s" does not match the allowed pattern', $name ) );
		}
	}

	/**
	 * Returns true if component is parent of given component
	 *
	 * @param Pie_Easy_Component $component
	 * @return boolean
	 */
	public function is_parent_of( Pie_Easy_Component $component )
	{
		return $this->__name__ == $component->parent;
	}

	/**
	 * Return the parent component (if set)
	 *
	 * Always check if parent is set first to avoid an exception being thrown
	 *
	 * @return Pie_Easy_Component
	 */
	public function get_parent()
	{
		// is a parent set
		if ( $this->parent ) {
			// yes, look it up from the registry and return
			return $this->policy()->registry()->get( $this->parent );
		} else {
			throw new Exception(
				sprintf( 'The "%s" component does not have a parent set', $this->name ) );
		}
	}

	/**
	 * Return all child components of this component
	 *
	 * @return array
	 */
	public function get_children()
	{
		return $this->policy()->registry()->get_children( $this );
	}

	/**
	 * Return sub-option of this component by passing ONLY the sub-option
	 * portion of the component name.
	 *
	 * When a sub-option is registered the delimeter is replaced with a hyphen.
	 *
	 * For example [cool-feature.color] results in the option name "cool-feature-color"
	 *
	 * To retrieve the option object simply call $feature->get_suboption('color');
	 *
	 * @return array
	 */
	public function get_suboption( $name )
	{
		// build up option name
		$option_name = sprintf( '%s-%s', $this->name, $name );

		// get and return it
		return $this->policy()->options()->registry()->get( $option_name );
	}

	/**
	 * Return array of variables to extract() for use by the template
	 *
	 * @return array
	 */
	public function get_template_vars()
	{
		// empty array by default
		return array();
	}
	
	/**
	 * Return absolute path to default template
	 *
	 * @return string
	 */
	final protected function get_template_path()
	{
		// template path to return
		$template = null;

		// try to locate the template
		if ( $this->template ) {
			$template = Pie_Easy_Scheme::instance()->locate_template( $this->template );
		}

		if ( $template ) {
			return $template;
		} else {
			return $this->policy()->factory()->ext_tpl( $this );
		}
	}

	/**
	 * Load the template (if it exists)
	 */
	final public function load_template()
	{
		// get template vars
		$__tpl_vars__ = $this->get_template_vars();

		// extract?
		if ( is_array( $__tpl_vars__ ) && !empty( $__tpl_vars__ ) ) {
			extract( $__tpl_vars__ );
		}

		// load template
		include( $this->get_template_path() );
	}
	
	/**
	 * Render this component
	 *
	 * @param boolean $output Whether to output or return result
	 * @return string|void
	 */
	public function render( $output = true )
	{
		if ( $this->ignore ) {
			throw new Exception( 'Cannot render a component that has been ignored' );
		} else {
			if ( $output === true ) {
				$this->policy()->renderer()->render( $this, true );
				return true;
			} else {
				return $this->policy()->renderer()->render( $this, $output );
			}
		}
	}

	/**
	 * Render this component in bypass mode
	 *
	 * @return Pie_Easy_Renderer
	 */
	public function render_bypass()
	{
		if ( $this->ignore ) {
			throw new Exception( 'Cannot render a component that has been ignored' );
		} else {
			return $this->policy()->renderer()->render_bypass( $this );
		}
	}

	/**
	 * Render wrapper classes
	 *
	 * @param string $class,...
	 */
	public function render_classes()
	{
		// get unlimited number of class args
		$classes = func_get_args();

		// append custom class if set
		if ( $this->class ) {
			$classes[] = $this->class;
		}

		// render them all delimited with a space
		print esc_attr( join( ' ', $classes ) );
	}
}

?>
