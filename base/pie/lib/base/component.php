<?php
/**
 * PIE API: base component class file
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
	'base/visitable',
	'ui/styleable',
	'ui/scriptable',
	'utils/export',
	'init/directive'
);

/**
 * Make content components easy
 *
 * @package PIE
 * @subpackage base
 * @property-read string $theme The theme that created this concrete component
 * @property-read string $name The concrete component name
 * @property-read string $type The concrete component type
 * @property-read string $parent The parent component (slug)
 * @property-read string $title The concrete component title
 * @property-read string $description The concrete component description
 * @property-read string $class The CSS class to apply to the component's container
 * @property-read boolean|string $documentation true/false to enable/disable, string for manual page name
 * @property-read array $capabilities Required capabilities, can only be appended
 * @property-read string $required_feature Feature required for this component to run/display
 * @property-read boolean $ignore Whether or not this component should be ignored
 * @property-read string $template Relative path to component template file
 * @property-read string $style Relative path to custom stylesheet
 * @property-read string $style_depends Comma separated list of style handles to enqueue
 * @property-read string $script Relative path to custom javascript source file
 * @property-read string $script_depends Comma separated list of script handles to enqueue
 */
abstract class Pie_Easy_Component
	extends Pie_Easy_Componentable
		implements Pie_Easy_Visitable, Pie_Easy_Configurable, Pie_Easy_Styleable, Pie_Easy_Scriptable
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
	 * The delimeter used for element ids.
	 */
	const ELEMENT_ID_DELIM = '-';

	/**
	 * The delimeter used for element classes.
	 */
	const ELEMENT_CLASS_DELIM = '-';

	/**
	 * Component configurations registry
	 *
	 * @var Pie_Easy_Init_Config
	 */
	private $__config__;
	
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
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $type The type (component extension) of this component
	 * @param string $theme The theme which originally created this component
	 * @param Pie_Easy_Policy $policy The policy to apply to this component
	 */
	final public function __construct( $name, $type, $theme, $policy )
	{
		// apply policy
		$this->policy( $policy );

		// init config and directives registries
		$this->__config__ = new Pie_Easy_Init_Config();
		$this->__directives__ = new Pie_Easy_Init_Directive_Registry();

		// init base config
		$this->config_array(
			array(
				'name' => $this->validate_name( $name ),
				'type' => $type,
				'theme' => $theme
			),
			$theme
		);

		// init read-only directives
		$this->directive( 'name', $this->config()->name, true, true );
		$this->directive( 'type', $this->config()->type, true, true );
		$this->directive( 'theme', $this->config()->theme, true, true );

		// init base directives
		$this->title = __( 'No title was configured', pie_easy_text_domain );
		$this->description = null;
		$this->documentation = null;
		$this->style = null;
		$this->style_depends = null;
		$this->script = null;
		$this->script_depends = null;
		$this->template = null;
		$this->class = null;
		$this->capabilities = null;
		$this->required_feature = null;
		$this->ignore = false;

		// init style and script objects
		$this->style();
		$this->script();

		// run init template method
		$this->init();
	}

	/**
	 */
	final public function __get( $name )
	{
		if ( $this->__directives__->has($name) ) {
			return $this->__directives__->get($name)->value;
		} else {
			return null;
		}
	}

	/**
	 */
	final public function __set( $name, $value )
	{
		// set directive
		return $this->directive( $name, $value );
	}

	/**
	 */
	final public function __isset( $name )
	{
		return $this->__directives__->has( $name );
	}

	/**
	 */
	final public function __unset( $name )
	{
		// not allowed
		throw new Exception(
			sprintf( 'The "%s" property cannot be unset', $name ) );
	}

	public function accept( Pie_Easy_Visitor $visitor )
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
	 * Return directives registry
	 *
	 * @return Pie_Easy_Init_Directive_Registry
	 */
	final protected function directives()
	{
		return $this->__directives__->get_all();
	}

	/**
	 * Return one directive
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $ro_value
	 * @param boolean $ro_theme
	 * @return mixed
	 */
	final protected function directive( $name = null, $value = null, $ro_value = false, $ro_theme = false )
	{
		// this method is silly flexible
		switch ( func_num_args() ) {

			// no args, return entire directive registry
			case 0:
				return $this->__directives__;

			// one arg, return value of one directive
			case 1:
				// get directive data
				$data = $this->__directives__->get( $name );
				// return data value or null
				return ( $data ) ? $data->value : null;

			// more than one arg, we are setting
			default:
				// get theme which set the config directive
				$config_data = $this->__config__->get( $name );
				// if theme is empty, assume origin theme set directive
				if ( $config_data ) {
					$theme = $config_data->theme;
				} else {
					$theme = $this->theme;
				}
				// set it
				return $this->__directives__->set( $theme, $name, $value, $ro_value, $ro_theme );
		}
	}
	
	/**
	 * Return style object
	 *
	 * @return Pie_Easy_Style
	 */
	public function style()
	{
		if ( !$this->__style__ instanceof Pie_Easy_Style ) {

			// init style object
			$this->__style__ = new Pie_Easy_Style( $this );
			
			// init style sections
			$this->__style__
				->add_section( 'global' )
				->add_section( 'admin' );
		}

		// return it!
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

			// init script object
			$this->__script__ = new Pie_Easy_Script( $this );

			// init script sections
			$this->__script__
				->add_section( 'global' )
				->add_section( 'admin' );
		}

		// return it!
		return $this->__script__;
	}

	/**
	 * Return entire config, or return/set the value of one item
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $ro_value
	 * @param boolean $ro_theme
	 * @return Pie_Easy_Init_Config|mixed
	 */
	final public function config( $name = null, $value = null, $ro_value = false, $ro_theme = false )
	{
		// this method is just silly flexible
		switch ( func_num_args() ) {

			// no args, return entire config
			case 0:
				return $this->__config__;

			// one arg, return value of one item
			case 1:
				return $this->__config__->get( $name )->value;

			// more than one arg, we are setting
			default:
				// get current theme scope
				$theme = $this->policy()->registry()->theme_scope();
				// set it
				return $this->__config__->set( $theme, $name, $value, $ro_value, $ro_theme );
		}
	}

	/**
	 * Push component config values on to the configuration
	 *
	 * @param array $config_array
	 */
	final public function config_array( $config_array )
	{
		// grab theme from configuration array
		$theme = $config_array['theme'];

		// is a feature set?
		if ( isset( $config_array['feature'] ) ) {
			// yes, check it for namespace defaults
			$feature = $this->policy()->features()->registry()->get( $config_array['feature'] );
			// try to grab defaults
			$defaults_array = $feature->config()->get_ns_defaults( $this->policy()->get_handle() );
			// get any defaults?
			if ( !empty( $defaults_array ) ) {
				// merge config *ON TOP OF* defaults
				$config_array = array_merge( $defaults_array, $config_array );
			}
		}

		foreach ( $config_array as $name => $value ) {

			// skip these base items
			switch ( $name ) {
				case 'theme':
				case 'name':
				case 'type':
					continue;
			}

			// set it
			$this->__config__->set( $theme, $name, $value );
		}
	}

	/**
	 */
	public function configure()
	{
		// get config
		$config = $this->config();

		// parent
		if ( $config->parent ) {
			if ( $this->name != $config->parent ) {
				$this->directive( 'parent', $config->parent );
			} else {
				throw new Exception(
					sprintf( 'The component "%s" cannot be a parent of itself', $this->name ) );
			}
		}
		
		// title
		if ( isset( $config->title ) ) {
			$this->title = $config->title;
		}

		// desc
		if ( isset( $config->description ) ) {
			$this->description = $config->description;
		}

		// documentation
		if ( isset( $config->documentation ) ) {
			$this->documentation = $config->documentation;
		}

		// set stylesheet
		if ( isset( $config->style ) ) {
			$this->style = $config->style;
			$this->style()->cache(
				$this->get_element_id(),
				Pie_Easy_Scheme::instance()->locate_file( $this->style )
			);
		}

		// set style dependancies
		if ( isset( $config->style_depends ) ) {
			// split deps at comma
			$deps = explode( ',', $config->style_depends );
			// set directive
			$this->style_depends = $deps;
		}

		// set script
		if ( isset( $config->script ) ) {
			$this->script = $config->script;
			$this->script()->cache(
				$this->get_element_id(),
				Pie_Easy_Scheme::instance()->locate_file( $this->script )
			);
		}

		// set script dependancies
		if ( isset( $config->script_depends ) ) {
			// split deps at comma
			$deps = explode( ',', $config->script_depends );
			// set directive
			$this->script_depends = $deps;
		}

		// set template
		if ( isset( $config->template ) ) {
			$this->template = $config->template;
		}

		// css class
		if ( isset( $config->class ) ) {
			$this->class = $config->class;
		}

		// capabilities
		if ( isset( $config->capabilities ) ) {
			$this->add_capabilities( $config->capabilities );
		}

		// required feature
		if ( isset( $config->required_feature ) ) {
			$this->required_feature = $config->required_feature;
		}

		// set ignore
		if ( isset( $config->ignore ) ) {
			$this->ignore = (boolean) $config->ignore;
		}

	}

	/**
	 * Run final component set up steps
	 */
	final public function finalize()
	{
		// lock configuration
		$this->config()->lock();

		// call configure template method
		$this->configure();

		// @todo should probably lock the directives here
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

		// capabilities are empty by default
		$capabilities = array();

		// trim and set each
		foreach ( $caps as $cap ) {
			$cap_trimmed = trim( $cap );
			$capabilities[$cap_trimmed] = $cap_trimmed;
		}

		if ( isset( $this->capabilities ) ) {
			// lookup map
			$theme_map = $this->__directives__->get_map( 'capabilities' );
			// get one?
			if ( $theme_map && $theme_map->item_at($this->theme)->value ) {
				$theme_caps = $theme_map->item_at($this->theme)->value;
				$theme_caps->merge_with( $capabilities );
			}
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
	 * This template method is called at the end of the constructor
	 */
	protected function init()
	{
		// override this method to initialize special PHP handling for a component
	}

	/**
	 * This template method is called immediately after component registration
	 * @internal
	 */
	public function init_registered()
	{
		// override this to perform post registration tasks
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
	 *
	 * Override this method to initialize special style handling for a component
	 */
	public function init_styles()
	{
		// depend on any styles?
		if ( $this->style_depends instanceof Pie_Easy_Map ) {
			// enqueue all of them
			foreach( $this->style_depends as $dep ) {
				wp_enqueue_style( $dep );
			}
		}
	}

	/**
	 * This template method is called "just in time" to enqueue scripts
	 *
	 * Override this method to initialize special script handling for a component
	 */
	public function init_scripts()
	{
		// depend on any scripts?
		if ( $this->script_depends instanceof Pie_Easy_Map ) {
			// enqueue all of them
			foreach( $this->script_depends as $dep ) {
				wp_enqueue_script( $dep );
			}
		}
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
	private function validate_name( $name )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+((_|-)[a-z0-9]+)*$/', $name ) ) {
			return $name;
		} else {
			throw new Exception( sprintf(
				'The %s name "%s" does not match the allowed pattern',
				$this->policy()->get_handle(), $name
			));
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
		return $this->name == $component->parent;
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
	 * @param string $name Name of the sub-option
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
	 * Check if suboption is registered
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public function has_suboption( $name )
	{
		// build up option name
		$option_name = sprintf( '%s-%s', $this->name, $name );

		// get and return it
		return $this->policy()->options()->registry()->has( $option_name );
	}

	/**
	 * Return unique css element id for this component
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function get_element_id()
	{
		// every id has at least these pieces
		$id = array(
			'pie',
			'easy',
			'exts',
			$this->policy()->get_handle(),
			str_replace( '/', self::ELEMENT_ID_DELIM, $this->type ),
			$this->name
		);
		
		// any additional args?
		if ( func_num_args() ) {
			// push extra delimeter so we get a triple char
			array_push( $id, self::ELEMENT_ID_DELIM );
			// add each suffix
			foreach( func_get_args() as $arg ) {
				array_push( $id, $arg );
			}
		}

		return esc_attr( implode( self::ELEMENT_ID_DELIM, $id ) );
	}

	/**
	 * Return css class for this component
	 *
	 * @param string $suffix,...
	 * @return string
	 */
	final public function get_element_class()
	{
		// every id has at least these pieces
		$class = array(
			'pie',
			'easy',
			'exts',
			$this->policy()->get_handle(),
			str_replace( '/', self::ELEMENT_CLASS_DELIM, $this->type ),
		);

		// any additional args?
		if ( func_num_args() ) {
			// add each suffix
			foreach( func_get_args() as $arg ) {
				array_push( $class, $arg );
			}
		}

		return esc_attr( implode( self::ELEMENT_CLASS_DELIM, $class ) );
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
	 * @param integer $ancestor Number of ancestories to skip, including self
	 * @return string
	 */
	final public function get_template_path( $ancestor = 0 )
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
			return $this->locate_file( 'template.php', $ancestor );
		}
	}
	
	/**
	 * Return path to an ext file
	 *
	 * @param string $filename
	 * @param integer $ancestor
	 * @return string
	 */
	final public function locate_file( $filename, $ancestor = 0 )
	{
		// what class am i?
		$r = new ReflectionClass($this);

		// i am the first class to check
		$ext_paths = array( $r->getFileName() );

		// loop class ancestry
		while( $parent_class = $r->getParentClass() ) {
			// load next ancestory
			$r = new ReflectionClass( $parent_class->name );
			// grandparent class
			$grandparent_class = $r->getParentClass();
			// break if grandparent class is base component
			if ( $grandparent_class->name == 'Pie_Easy_Component') {
				break;
			}
			// push parent class onto classes
			array_push( $ext_paths, $parent_class->getFileName() );
		}

		// loop all ext paths
		foreach( $ext_paths as $ext_path ) {

			// skip this ancestor?
			if ( $ancestor-- >= 1 ) {
				continue;
			}

			// get parent directory of class file path
			$ext_dir = dirname( $ext_path );

			// get extension type from path
			$ext_type = substr( $ext_dir, strrpos( $ext_dir, $this->policy()->get_handle() ) );

			// look for scheme files first
			$file_theme =
				Pie_Easy_Scheme::instance()->locate_extension_file(
					$ext_type,
					$filename
				);

			// find a theme file?
			if ( $file_theme ) {
				// yes, use that one
				return $file_theme;
			} else {
				// no, try for default location
				$file_default = PIE_EASY_LIBEXT_DIR . '/' . $ext_type . '/' . $filename;
				// exists?
				if ( Pie_Easy_Files::cache($file_default)->is_readable() ) {
					return $file_default;
				}
			}
		}

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
		if ( $this->ignore ) {
			throw new Exception( 'Cannot render a component that has been ignored' );
		} elseif ( $this->renderable() ) {
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
 * Abstract asset exporter
 *
 * @package PIE
 * @subpackage schemes
 */
abstract class Pie_Easy_Component_Asset_Export
	extends Pie_Easy_Export
		implements Pie_Easy_Visitor
{
	// nothing special yet
}

/**
 * Style exporter
 *
 * @package PIE
 * @subpackage schemes
 */
class Pie_Easy_Component_Style_Export
	extends Pie_Easy_Component_Asset_Export
{
	public function visit( Pie_Easy_Visitable $visited )
	{
		if ( $visited->supported() ) {
			$visited->init_styles();
			$this->push( $visited->style() );
		}
	}
}

/**
 * Script exporter
 *
 * @package PIE
 * @subpackage schemes
 */
class Pie_Easy_Component_Script_Export
	extends Pie_Easy_Component_Asset_Export
{
	public function visit( Pie_Easy_Visitable $visited )
	{
		if ( $visited->supported() ) {
			$visited->init_scripts();
			$this->push( $visited->script() );
		}
	}
}

?>
