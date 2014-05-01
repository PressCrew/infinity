<?php
/**
 * ICE API: base component class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage base
 * @since 1.0
 */

ICE_Loader::load(
	'base/componentable',
	'base/recursable',
	'base/visitable',
	'dom/element',
	'dom/style',
	'dom/script',
	'utils/export',
	'init/settings'
);

/**
 * Make content components easy
 *
 * @package ICE
 * @subpackage base
 */
abstract class ICE_Component
	extends ICE_Componentable
		implements ICE_Recursable, ICE_Visitable
{
	/**
	 * Persistence algorithms should use this as a prefix
	 */
	const API_PREFIX = 'icext';

	/**
	 * Persistence algorithms should use this as a delimeter where valid
	 */
	const API_DELIM = '.';

	/**
	 * Name of the default section
	 */
	const DEFAULT_SECTION = 'default';

	/**
	 * Name of the default templates subdir
	 */
	const DEFAULT_TEMPLATE_DIR = 'templates';
	
	/**
	 * Component's DOM element helper
	 *
	 * @var ICE_Component_Element
	 */
	private $__element__;

	/**
	 * The component atypical name which can be used to identify this component instance across components and persistently across requests
	 *
	 * @var string
	 */
	private $aname;

	/**
	 * A body class to add when this component is active.
	 *
	 * @var string
	 */
	protected $body_class;

	/**
	 * Required capabilities, can only be appended
	 *
	 * @var array
	 */
	private $capabilities;

	/**
	 * The CSS class(es) to apply to the component's container
	 *
	 * @var array
	 */
	protected $class;
	
	/**
	 * The component description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Set to true/false to enable/disable, or to a string for manual page name
	 *
	 * @var boolean
	 */
	protected $documentation;

	/**
	 * The component hash name. A crc32 hash of the aname in hex format
	 *
	 * @var string
	 */
	private $hname;

	/**
	 * The CSS id to set for the component's container
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The parent component (slug)
	 *
	 * @var string
	 */
	protected $parent;

	/**
	 * The component name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Feature required for this component to run/display
	 *
	 * @var string
	 */
	protected $required_feature;

	/**
	 * Relative path to custom javascript source file
	 *
	 * @var string
	 */
	protected $script;

	/**
	 * Array of script handles to pass to enqueuer as dependencies.
	 *
	 * @var array
	 */
	protected $script_depends = array();

	/**
	 * Relative path to custom stylesheet
	 *
	 * @var string
	 */
	protected $style;

	/**
	 * Array of style handles to pass to enqueuer as dependencies.
	 *
	 * @var array
	 */
	protected $style_depends = array();
	
	/**
	 * Relative path to component template file
	 *
	 * @var string
	 */
	protected $template;

	/**
	 * The component title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * The component type
	 *
	 * @var string
	 */
	private $type;

	/**
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $type The extension type of this component
	 * @param ICE_Policy $policy The policy to apply to this component
	 * @throws ICE_Requirements_Exception
	 * @throws ICE_Initialization_Exception
	 * @throws ICE_Capabilities_Exception
	 */
	final public function __construct( $name, $type, $policy )
	{
		// apply policy
		$this->policy( $policy );
		
		// check requirements
		if ( false === $this->check_reqs() ) {
			// missing reqs
			throw new ICE_Requirements_Exception(
				sprintf( 'Cannot construct the "%s" %s component: requirements check failed', $name, $type ) );
		}

		// init required directives
		$this->name = $this->validate_name( $name );

		// the "atypical name" is unique across all components
		$this->aname = $this->format_aname( $this->name );

		// the "hash name" is the crc32 hex hash of the aname
		$this->hname = $this->format_hname( $this->aname );

		// the type of this component
		$this->type = $type;

		// call configure template method
		$this->configure();

		// check support
		if ( false === $this->check_support() ) {
			// not supported
			throw new ICE_Initialization_Exception(
				sprintf( 'Cannot construct the "%s" %s component: support check failed', $name, $type ) );
		}

		// check caps
		if ( false === $this->check_caps() ) {
			// not enough permission
			throw new ICE_Capabilities_Exception(
				sprintf( 'Cannot construct the "%s" %s component: capabilities check failed', $name, $type ) );
		}
	}

	/**
	 * Return an atypical name for the given component name
	 *
	 * @param string $name
	 * @return string
	 */
	protected function format_aname( $name = null )
	{
		return $this->_policy->get_handle( false ) . '/' . $name;
	}

	/**
	 * Return a hash name for the given component atypical name
	 *
	 * @param type $aname
	 * @return type
	 */
	protected function format_hname( $aname )
	{
		return hash( 'crc32', $aname );
	}

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'aname':
			case 'body_class':
			case 'capabilities':
			case 'class':
			case 'description':
			case 'documentation':
			case 'hname':
			case 'id':
			case 'name':
			case 'parent':
			case 'required_feature':
			case 'script':
			case 'script_depends':
			case 'style':
			case 'style_depends':
			case 'template':
			case 'title':
			case 'type':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 *
	protected function set_property( $name, $value )
	{
		switch ( $name ) {
			case 'foobar':
				// set it
				$this->$name = $value;
				// chain it
				return $this;
			default:
				return parent::set_property( $name, $value );
		}
	}
	 *
	 */

	/**
	 */
	public function accept( ICE_Visitor $visitor )
	{
		return $visitor->visit( $this );
	}

	/**
	 * Return current version of this component
	 *
	 * You must define the VERSION class constant or it will return the version of
	 * the parent component class, or 0 if no version is found at all.
	 *
	 * @return int
	 */
	final public function version()
	{
		if ( defined('self::VERSION') ) {
			return self::VERSION;
		} else {
			return 0;
		}
	}

	/**
	 * Copy configuration settings to properties.
	 *
	 * This method only works for properties which are *locally accessible*, which means
	 * they must have been declared as protected or public (even though public would be silly!).
	 *
	 * Example: $this->import_settings( array( 'foo' => 'string' ) );
	 *
	 * @param array $settings Settings to copy from the configuration.
	 * @param array $types Map of settings to their type. Valid types are: string|integer|float|boolean|array
	 * @return boolean
	 */
	final protected function import_settings( $config, $types = array() )
	{
		// get all raw setting values
		$raw_values = $this->_registry->get_settings( $this->name );

		// loop raw values, NOT settings
		foreach ( $raw_values as $setting => $raw_value ) {
			// is conf data set?
			if ( in_array( $setting, $config ) ) {
				// type cast?
				if ( isset( $types[ $setting ] ) ) {
					// try to cast it
					try {
						// call cast helper
						$this->$setting = $this->cast( $raw_value, $types[ $setting ] );
					} catch ( Exception $e ) {
						// type cast failed, value NOT set
						continue;
					}
				} else {
					// no type passed, use value as is
					$this->$setting = $raw_value;
				}
			}
		}
	}

	/**
	 * Return element helper instance
	 *
	 * @return ICE_Component_Element
	 */
	public function element()
	{
		if ( !$this->__element__ instanceof ICE_Component_Element ) {
			// new element object
			$this->__element__ = new ICE_Component_Element();
			// initialize the element
			$this->init_element();
		}

		// return it!
		return $this->__element__;
	}

	/**
	 */
	protected function configure()
	{
		// import settings
		$this->import_settings(
			array(
				'body_class',
				'capabilities',
				'class',
				'description',
				'documentation',
				'id',
				'parent',
				'required_feature',
				'script',
				'script_depends',
				'style',
				'style_depends',
				'template',
				'title'
			),
			array(
				'capabilities' => 'array',
				'class' => 'array',
				'documentation' => 'boolean',
				'script_depends' => 'array',
				'style_depends' => 'array'
			)
		);

		// parent sanity check
		if (
			true === isset( $this->parent ) &&
			$this->name == $this->parent
		) {
			throw new Exception(
				sprintf( 'The component "%s" cannot be a parent of itself', $this->name ) );
		}
	}

	/**
	 * Run final component set up steps
	 *
	 * @todo should probably lock the directives here
	 */
	final public function finalize()
	{
		// maybe hook up body class
		if ( $this->body_class ) {
			// do it
			add_filter( 'body_class', array( $this, 'add_body_class' ) );
		}
	}

	/**
	 * Append body class to given array.
	 *
	 * @param array $classes
	 * @return array
	 */
	public function add_body_class( $classes )
	{
		// append to classes array
		$classes[] = esc_attr( $this->body_class );
		// all done
		return $classes;
	}

	/**
	 * Set additional capabilities which are required for this option to show
	 *
	 * @todo needs a lot of testing
	 * @param string $string A comma separated list of capabilities
	 */
	final public function add_capabilities( $string )
	{
		// caps only apply to administration of components for now
		if ( !is_admin() ) {
			return;
		}

		// split at comma
		$caps = explode( ',', $string );

		// capabilities are empty by default
		$capabilities = array();

		// trim and set each
		foreach ( $caps as $cap ) {
			$cap_trimmed = trim( $cap );
			$capabilities[$cap_trimmed] = $cap_trimmed;
		}

		if ( isset( $this->capabilities ) ) {
			// merge them
			$this->capabilities = array_merge( $this->capabilities, $capabilities );
		} else {
			$this->capabilities = $capabilities;
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
	 * Check that all external requirements of this component are met.
	 *
	 * This method is executed in the constructor before any other logic!
	 *
	 * @return boolean
	 */
	public function check_reqs()
	{
		return true;
	}

	/**
	 * This template method is called on the WordPress 'init' action.
	 */
	public function init()
	{
		// setup auto styles
		$this->setup_auto_style();
		// setup auto scripts
		$this->setup_auto_script();
	}

	/**
	 * Set up the element helper instance
	 */
	final protected function init_element()
	{
		// determine id
		if ( $this->id ) {
			$element_id = $this->id;
		} else {
			$element_id = self::API_PREFIX . '-' . $this->hname;
		}
		
		// set preferences
		$this->element()
			->set_id( $element_id )
			->set_slug( self::API_PREFIX )
			->set_class_suffix_offset( 1 );

		// component type
		$comp_type = $this->_policy->get_handle( false );

		// classes start with abstract component type
		$this->element()->add_class( array( self::API_PREFIX, $comp_type ) );

		// get ancestors
		$ancestors = $this->_policy->extensions()->get_ancestory( $this->type );

		// loop ancestors
		while ( $ancestors ) {
			// get class parts by splitting at slashes
			$class_parts = explode( '/', array_pop( $ancestors ) );
			// css class
			$class = array_merge(
				array( self::API_PREFIX ),
				$class_parts
			);
			// add element class
			$this->element()->add_class( $class );
		}
		
		// add custom class if set
		if ( $this->class ) {
			$this->element()->add_class( $this->class );
		}

		// no more messing with element allowed
		$this->element()->lock();
	}

	/**
	 * Setup automatic style enqueuing.
	 */
	protected function setup_auto_style()
	{
		// is a style set?
		if ( $this->style ) {
			// locate it
			$path = ICE_Scheme::instance()->locate_file( $this->style );
			// find it?
			if ( $path ) {
				// yep, register it
				ice_register_style(
					$this->name,
					array(
						'src' => ICE_Files::file_to_site_url( $path ),
						'deps' => $this->style_depends
					)
				);
			}
		}
	}

	/**
	 * Setup automatic script enqueuing.
	 */
	protected function setup_auto_script()
	{
		// is a script set?
		if ( $this->script ) {
			// locate it
			$path = ICE_Scheme::instance()->locate_file( $this->script );
			// find it?
			if ( $path ) {
				// yep, register it
				ice_register_script(
					$this->name,
					array(
						'src' => ICE_Files::file_to_site_url( $path ),
						'deps' => $this->script_depends,
						'in_footer' => true
					)
				);
			}
		}
	}

	/**
	 * Check that component is supported, varies by component
	 *
	 * @return boolean
	 */
	public function check_support()
	{
		// is a required feature set?
		if ( null !== $this->required_feature ) {
			// check for theme support
			if ( false == current_theme_supports( $this->required_feature ) ) {
				// no theme support
				return false;
			}
		}

		// ok by default
		return true;
	}

	/**
	 * Set the name
	 *
	 * @param string $name
	 */
	final protected function validate_name( $name )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z][a-z0-9]*((_|-)[a-z0-9]+)*$/', $name ) ) {
			return $name;
		} else {
			throw new Exception( sprintf(
				'The %s name "%s" does not match the allowed pattern',
				$this->_policy->get_handle(), $name
			));
		}
	}

	/**
	 * Returns true if component is parent of given component
	 *
	 * @param ICE_Component $component
	 * @return boolean
	 */
	public function is_parent_of( ICE_Component $component )
	{
		return $this->name == $component->get_property( 'parent' );
	}

	/**
	 * Return the parent component (if set)
	 *
	 * Always check if parent is set first to avoid an exception being thrown
	 *
	 * @return ICE_Component
	 */
	public function parent()
	{
		// is a parent set
		if ( $this->parent ) {
			// yes, look it up from the registry and return
			return $this->_registry->get( $this->parent );
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
		return $this->_registry->get_children( $this );
	}

	/**
	 * Format a suboption name using the glue character defined in the registry
	 *
	 * @param string $name
	 * @return string
	 */
	public function format_suboption( $name )
	{
		// call registry suboption format helper
		return ICE_Registry::format_suboption( $this->name, $name );
	}

	/**
	 * Return sub-option of this component by passing ONLY the sub-option
	 * portion of the component name.
	 *
	 * To retrieve the "color" option object, simply call $feature->get_suboption('color');
	 *
	 * @param string $name Name of the sub-option
	 * @return array
	 */
	public function get_suboption( $name )
	{
		// build up option name
		$option_name = $this->format_suboption( $name );

		// get and return it
		return $this->_policy->options()->registry()->get( $option_name );
	}

	/**
	 * Check if suboption is registered
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function has_suboption( $name )
	{
		// build up option name
		$option_name = $this->format_suboption( $name );

		// get and return it
		return $this->_policy->options()->registry()->has( $option_name );
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
	final public function get_template_path()
	{
		// template path to return
		$template = null;

		// try to locate the template
		if ( $this->template ) {
			// was absolute path given?
			if ( ICE_Files::path_is_absolute( $this->template ) ) {
				// yep, use as is
				$template = $this->template;
			} else {
				// its relative, try to locate it
				$template = ICE_Scheme::instance()->locate_template( $this->template );
			}
		}

		// was a template found?
		if ( $template ) {
			// yes! use that one
			return $template;
		} else {
			// try to locate the default template
			return $this->_policy->extensions()->get_template_path( $this->type );
		}
	}

	/**
	 * Return absolute path to a template part
	 *
	 * @param string $name
	 * @return string|false
	 */
	final public function get_template_part( $name )
	{
		// only allow sane characters
		if ( preg_match( '/[a-z0-9]+[\w-]*/', $name ) ) {
			// try to locate it!
			return $this->locate_file( sprintf( 'template-%s.php', $name ) );
		}

		// bad name
		throw new Exception( sprintf(
			'The template part named "%s" contains invalid characters', $name ) );
	}
	
	/**
	 * Return path to an ext file
	 *
	 * @param string $filename
	 * @return string
	 */
	final public function locate_file( $filename )
	{
		return $this->_policy->extensions()->locate_file( $this->type, $filename );
	}

	/**
	 * Return URL to an ext file
	 *
	 * @param string $filename
	 * @return string|false
	 */
	final public function locate_file_url( $filename )
	{
		// locate the file path
		$path = $this->locate_file( $filename );

		// was a path found?
		if ( $path ) {
			// yes, use files util to get URL
			return ICE_Files::file_to_site_url( $path );
		}

		// no file found
		return false;
	}

	/**
	 * Render this component
	 *
	 * @param boolean $output Whether to output or return result
	 * @return string|void
	 */
	public function render( $output = true )
	{
		if ( $this->renderable() ) {
			if ( $output === true ) {
				$this->_renderer->render( $this, true );
				return true;
			} else {
				return $this->_renderer->render( $this, $output );
			}
		} else {
			// not renderable
			return false;
		}
	}

	/**
	 * Render this component in bypass mode
	 *
	 * @return ICE_Renderer
	 */
	public function render_bypass()
	{
		return $this->_renderer->render_bypass( $this );
	}

	/**
	 * Returns true if the component believes rendering will be successful
	 *
	 * Override this method to do a last second check to make sure all
	 * necessary data is available to execute a successful render.
	 *
	 * @return boolean
	 */
	public function renderable()
	{
		return true;
	}

}

/**
 * Component element
 *
 * @package ICE
 * @subpackage base
 */
class ICE_Component_Element extends ICE_Element
{
	// nothing special yet, but there will be!
}
