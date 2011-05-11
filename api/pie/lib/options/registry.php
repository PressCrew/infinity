<?php
/**
 * PIE API: options registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'utils/ajax', 'collections' );

/**
 * Make keeping track of options easy
 *
 * @package PIE
 * @subpackage options
 */
abstract class Pie_Easy_Options_Registry
{
	/**
	 * The prefix that denotes a section name in the config
	 */
	const SECTION_PREFIX = '.';

	/**
	 * The string on which to split field option key => values
	 */
	const FIELD_OPTION_DELIM = '=';

	/**
	 * Name of parameter which passes back blog id
	 */
	const PARAM_BLOG_ID = 'pie_easy_options_blog_id';

	/**
	 * Name of parameter which passes back blog theme
	 */
	const PARAM_BLOG_THEME = 'pie_easy_options_blog_theme';

	/**
	 * Stack of config files that have been loaded
	 *
	 * @var Pie_Easy_Stack
	 */
	private $files_loaded;

	/**
	 * Name of the theme currently being loaded
	 *
	 * @var string
	 */
	private $loading_theme;

	/**
	 * The class to use for new sections
	 *
	 * @var string
	 */
	private $section_class;

	/**
	 * The class use for new options
	 *
	 * @var string
	 */
	private $option_class;

	/**
	 * The option renderer instance
	 *
	 * @var Pie_Easy_Options_Option_Renderer
	 */
	private $option_renderer;

	/**
	 * All sections that are currently configured
	 *
	 * @var Pie_Easy_Map|null
	 */
	private $sections;

	/**
	 * All options that are currently configured
	 *
	 * @var Pie_Easy_Map|null
	 */
	private $options;

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
	 * Initializes map properties
	 */
	public function __construct()
	{
		// initiate the maps
		$this->files_loaded = new Pie_Easy_Stack();
		$this->sections = new Pie_Easy_Map();
		$this->options = new Pie_Easy_Map();
	}

	/**
	 * Init screen dependencies for all applicable options to be rendered
	 */
	public function init_screen()
	{
		global $blog_id;

		$this->screen_blog_id = (integer) $blog_id;
		$this->screen_blog_theme = get_stylesheet();

		add_action( 'pie_easy_enqueue_styles', array($this, 'init_styles') );
		add_action( 'pie_easy_enqueue_scripts', array($this, 'init_scripts') );

		$this->option_renderer->init_screen();
	}

	/**
	 * Init ajax requirements
	 */
	public function init_ajax()
	{
		$this->option_renderer->init_ajax();
	}

	/**
	 * Enqueue required styles
	 */
	public function init_styles() {}

	/**
	 * Enqueue required scripts
	 */
	public function init_scripts()
	{
		// enqueue any scripts here
		// BEFORE LOCALIZING!!!

		// localize the upload wrapper
		$this->localize_script();
	}

	/**
	 * Localize the ajax url
	 */
	protected function localize_script() {}

	/**
	 * Set the PHP class to use for creating new sections
	 *
	 * @param string $class_name
	 * @return boolean
	 */
	public function set_section_class( $class_name )
	{
		// set the option class
		if ( class_exists( $class_name ) ) {
			$this->section_class = $class_name;
			return true;
		} else {
			throw new Exception( 'Provided section class does not exist' );
		}
	}

	/**
	 * Set the PHP class to use for creating new options
	 *
	 * @param string $class_name
	 * @return boolean
	 */
	public function set_option_class( $class_name )
	{
		// set the option class
		if ( class_exists( $class_name ) ) {
			$this->option_class = $class_name;
			return true;
		} else {
			throw new Exception( 'Provided option class does not exist' );
		}
	}

	/**
	 * Set the option renderer by passing a valid renderer object
	 *
	 * @param Pie_Easy_Options_Option_Renderer $renderer
	 */
	public function set_option_renderer( Pie_Easy_Options_Option_Renderer $renderer )
	{
		$this->option_renderer = $renderer;
	}

	/**
	 * Register an option
	 *
	 * @param Pie_Easy_Options_Option $option
	 * @return boolean
	 */
	private function register_option( Pie_Easy_Options_Option $option )
	{
		// has the option already been registered?
		if ( $this->has_option( $option->name ) ) {

			// get options stack
			$options_stack = $this->get_option_stack( $option->name );

			// check if option already registered for this theme
			if ( $options_stack->contains( $option->theme ) ) {
				throw new Exception( sprintf(
					'The "%s" option has already been registered for the "%s" theme',
					$option->name, $option->theme ) );
			}

		} else {
			$options_stack = new Pie_Easy_Stack();
			$this->options->add( $option->name, $options_stack );
		}

		// register it
		$options_stack->push( $option );
		return true;
	}

	/**
	 * Add a section
	 *
	 * @param Pie_Easy_Options_Section $section
	 * @return boolean
	 */
	private function register_section( Pie_Easy_Options_Section $section )
	{
		// make sure that the section has not already been registered
		if ( $this->sections->contains( $section->name ) ) {
			throw new Exception( sprintf( 'The "%s" section has already been registered', $section->name ) );
		}

		// register it
		$this->sections->add( $section->name, $section );
		return true;
	}

	/**
	 * Returns true if a section has been registered
	 *
	 * @param string $section_name
	 * @return boolean
	 */
	public function has_section( $section_name )
	{
		return $this->sections->contains( $section_name );
	}

	/**
	 * Return a registered section by name
	 *
	 * @param string $section_name
	 * @return Pie_Easy_Options_Section
	 */
	public function get_section( $section_name )
	{
		// check section registry
		if ( $this->sections->contains( $section_name ) ) {
			// return it
			return $this->sections->item_at( $section_name );
		}

		throw new Exception( sprintf( 'Unable to get section "%s": not registered', $section_name ) );
	}

	/**
	 * Return all registered sections as an array
	 *
	 * @return array
	 */
	public function get_sections()
	{
		return $this->sections->to_array();
	}

	/**
	 * Return all registered child sections of a section
	 *
	 * This adheres to parent settings in the options ini file
	 *
	 * @param Pie_Easy_Options_Section $section The section object whose children you want to get
	 * @return array
	 */
	public function get_section_children( Pie_Easy_Options_Section $section )
	{
		// the sections that will be returned
		$sections = array();

		// find all registered sections where parent is the target section
		foreach ( $this->sections as $section_i ) {
			if ( $section->is_parent_of( $section_i ) ) {
				$sections[] = $section_i;
			}
		}

		return $sections;
	}

	/**
	 * Get sections that should behave as a root section
	 *
	 * This method mostly exists as a helper to use when rendering menus
	 *
	 * @param array $section_names An array of section names to include, defaults to all
	 * @return array
	 */
	public function get_root_sections( $section_names = array() )
	{
		// sections to be returned
		$sections = array();

		// loop through all registered sections
		foreach ( $this->sections as $section ) {
			// filter on section names
			if ( empty( $section_names ) || in_array( $section->name, $section_names, true ) ) {
				$sections[] = $section;
			}
		}

		// don't return sections who have a parent in the result
		foreach( $sections as $key => $section_i ) {
			foreach( $sections as $section_ii ) {
				if ( $section_ii->is_parent_of( $section_i ) ) {
					unset( $sections[$key] );
				}
			}
		}

		return $sections;
	}

	/**
	 * Unregister an option
	 *
	 * @param string $option_name
	 * @return boolean
	 */
	private function unregister_option( $option_name )
	{
		if ( $this->has_option( $option_name ) ) {
			$this->options->remove( $option_name );
			return true;
		}

		return false;
	}

	/**
	 * Returns true if an option has been registered
	 *
	 * @param string $option_name
	 * @return boolean
	 */
	public function has_option( $option_name )
	{
		return $this->options->contains( $option_name );
	}

	/**
	 * Return a registered option object by name
	 *
	 * @param string $option_name
	 * @return Pie_Easy_Options_Option
	 */
	public function get_option( $option_name )
	{
		// check option registry
		if ( $this->has_option( $option_name ) ) {
			// from top of options stack
			return $this->get_option_stack($option_name)->peek();
		}

		// didn't find the option
		throw new Exception( sprintf( 'Unable to get option "%s": not registered', $option_name ) );
	}

	/**
	 * Return registered options as an array
	 *
	 * @param Pie_Easy_Options_Section $section Limit options to one section by passing a section object
	 * @return array
	 */
	public function get_options( Pie_Easy_Options_Section $section = null )
	{
		// options to return
		$options = array();

		// loop through and compare names
		foreach ( $this->options as $option_stack ) {

			// use option on top of stack
			$option = $option_stack->peek();

			// specific section?
			if ( $section ) {
				// do section names match?
				if ( $section->name != $option->section ) {
					continue;
				}
			}

			// add to array
			$options[] = $option;
		}

		// return them
		return $options;
	}

	/**
	 * Return options stack for the given option name
	 *
	 * @param string $option_name
	 * @return Pie_Easy_Stack
	 */
	private function get_option_stack( $option_name )
	{
		return $this->options->item_at( $option_name );
	}

	/**
	 * Return registered options that are valid in a menu
	 *
	 * It does not make sense to list an option in a menu which requires another option,
	 * so this helper method will return an array without them.
	 *
	 * @param Pie_Easy_Options_Section $section Limit options to one section
	 * @return array
	 */
	public function get_menu_options( Pie_Easy_Options_Section $section = null )
	{
		// get all options for section
		$options = $this->get_options( $section );

		foreach ( $options as $key => $option ) {
			// remove options that require another option
			if ( $option->required_option ) {
				unset( $options[$key] );
			}
			// remove options that aren't supported
			if ( !$option->supported() ) {
				unset( $options[$key] );
			}
			// remove options that fail caps check
			if ( !$option->check_caps() ) {
				unset( $options[$key] );
			}
		}

		return $options;
	}

	/**
	 * Load option directives from an ini file
	 *
	 * @uses parse_ini_file()
	 * @param string $filename Absolute path to the options ini file to parse
	 * @param string $theme The theme to assign the prased option directives to
	 * @return boolean
	 */
	public function load_config_file( $filename, $theme )
	{
		// skip loaded files
		if ( $this->files_loaded->contains( $filename ) ) {
			return;
		} else {
			$this->files_loaded->push( $filename );
		}

		// set the current theme being loaded
		$this->loading_theme = $theme;

		// try to parse the file
		return $this->load_config_array( parse_ini_file( $filename, true ) );
	}

	/**
	 * Load options into registry from an array (of parsed ini sections)
	 *
	 * @param array $ini_array
	 * @return boolean
	 */
	private function load_config_array( $ini_array )
	{
		// an array means successful parse
		if ( is_array( $ini_array ) ) {
			// loop through each option
			foreach ( $ini_array as $s_name => $s_config ) {
				// is it a section?
				if ( self::SECTION_PREFIX == $s_name{0} ) {
					$this->load_config_section( $s_name, $s_config );
				} else {
					$this->load_config_option( $s_name, $s_config );
				}
			}
			// all done
			return true;
		}

		return false;
	}

	/**
	 * Load a single option into the registry (one parsed ini section)
	 *
	 * @param string $option_name
	 * @param array $option_config
	 * @return boolean
	 */
	private function load_config_option( $option_name, $option_config )
	{
		// if option has already been registered, deep copy that option
		// and possibly override some values
		if ( $this->has_option( $option_name ) ) {

			// get source option, which is on top of the options stack
			$source_option = $this->get_option_stack($option_name)->peek();

			// copy option properties (do NOT use cloning)
			$option = new $this->{option_class}(
				$this->loading_theme,
				$source_option->name,
				$source_option->title,
				$source_option->description,
				$source_option->field_type,
				$source_option->section
			);

		} else {

			// get section from section registry
			$section = $this->sections->item_at( $option_config['section'] );

			// adding options to parent sections is not allowed
			foreach ( $this->sections as $section_i ) {
				if ( $section->is_parent_of( $section_i ) ) {
					throw new Exception(
						sprintf( 'Cannot add options to section "%s" because it is acting as a parent section', $section->name ) );
				}
			}

			// create new option
			$option = new $this->{option_class}(
				$this->loading_theme,
				$option_name,
				$option_config['title'],
				$option_config['description'],
				$option_config['field_type'],
				$section->name
			);

		}

		// register it
		$this->register_option( $option );

		// required option
		if ( isset( $option_config['required_option'] ) ) {
			$option->set_required_option( $option_config['required_option'] );
		}

		// required feature
		if ( isset( $option_config['required_feature'] ) ) {
			$option->set_required_feature( $option_config['required_feature'] );
		}

		// container class
		if ( isset( $option_config['class'] ) ) {
			$option->set_class( $option_config['class'] );
		}

		// default value
		if ( isset( $option_config['default_value'] ) ) {
			$option->set_default_value( $option_config['default_value'], $this->loading_theme );
		}

		// capabilities
		if ( isset( $option_config['capabilities'] ) ) {
			$option->add_capabilities( $option_config['capabilities'] );
		}

		// documentation
		if ( isset( $option_config['documentation'] ) ) {
			$option->set_documentation( $option_config['documentation'] );
		}

		// css id
		if ( isset( $option_config['field_id'] ) ) {
			$option->set_field_id( $option_config['field_id'] );
		}

		// css class
		if ( isset( $option_config['field_class'] ) ) {
			$option->set_field_class( $option_config['field_class'] );
		}

		// options
		if ( isset( $option_config['field_options'] ) ) {

			if ( is_array( $option_config['field_options'] ) ) {

				// loop through all field options
				foreach ( $option_config['field_options'] as $field_option ) {
					// split each one at the delimeter
					$field_option = explode( self::FIELD_OPTION_DELIM, $field_option, 2 );
					// add to array
					$field_options[trim($field_option[0])] = trim($field_option[1]);
				}

			} elseif ( strlen( $option_config['field_options'] ) ) {

				// possibly a function
				$callback = $option_config['field_options'];

				// check if the function exists
				if ( function_exists( $callback ) ) {
					// call it
					$field_options = $callback();
					// make sure we got an array
					if ( !is_array( $field_options ) ) {
						throw new Exception( sprintf( 'The field options callback function "%s" did not return an array', $callback ) );
					}
				} else {
					throw new Exception( sprintf( 'The field options callback function "%s" does not exist', $callback ) );
				}

			} else {
				throw new Exception( sprintf( 'The field options for the "%s" option is not configured correctly', $option_name ) );
			}

			// make sure we ended up with some options
			if ( count( $field_options ) >= 1 ) {
				// finally set them for the option
				$option->set_field_options( $field_options );
			}
		}

		return true;
	}

	/**
	 * Load a section into the registry
	 *
	 * @param string $section_name
	 * @param string $section_config
	 * @return boolean
	 */
	private function load_config_section( $section_name, $section_config )
	{
		// create new section
		$section = new $this->{section_class}(
			trim( $section_name, self::SECTION_PREFIX ),
			$section_config['title']
		);

		// css class
		if ( isset( $section_config['class'] ) ) {
			$section->set_class( $section_config['class'] );
		}

		// css title class
		if ( isset( $section_config['class_title'] ) ) {
			$section->set_class_title( $section_config['class_title'] );
		}

		// css content class
		if ( isset( $section_config['class_content'] ) ) {
			$section->set_class_content( $section_config['class_content'] );
		}

		// section parent
		if ( isset( $section_config['parent'] ) ) {
			$section->set_parent( $section_config['parent'] );
		}

		// register it
		return $this->register_section( $section );
	}

	/**
	 * Render one option given it's name
	 *
	 * @uses Pie_Easy_Options_Option_Renderer::render()
	 * @param string $option The option to render
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string|boolean
	 */
	public function render_option( $option, $output = true )
	{
		if ( is_string( $option ) ) {
			// get the option from map
			$option = $this->get_option( $option );
		} elseif ( !$this->has_option( $option->name ) ) {
			// not good
			throw new Exception( sprintf( 'The "%s" option is not registered', $option->name ) );
		}

		// render the option
		$html = $this->option_renderer->render( $option, $output );

		// render options that require this one
		foreach ( $this->get_options() as $sibling_option ) {
			if ( $option->name == $sibling_option->required_option ) {
				$html .= $this->option_renderer->render( $sibling_option, $output );
			}
		}

		// render the manifest
		$html .= $this->option_renderer->render_manifest( $output );

		// return result
		return ( $output ) ? true : $html;

	}

	/**
	 * Look through POST vars for options from this registry and try to save them
	 *
	 * @return integer Number of options saved
	 */
	public function process_form()
	{
		if ( empty( $_POST ) ) {
			return false;
		} elseif ( isset( $_POST['_manifest_'] ) ) {

			// load manifest
			$manifest = explode( ',', $_POST['_manifest_'] );

			// "save only these" option names if param is set
			$save_options =
				!empty( $_POST['option_names'] ) ?
				explode( ',', $_POST['option_names'] ) : null;

			// keep track of how many were updated
			$save_count = 0;

			// loop through manifest options
			foreach ( $manifest as $option_name ) {

				// skip options that don't exist in save options if set
				if ( !empty( $save_options ) && !in_array( $option_name, $save_options ) ) {
					continue;
				}

				// is this option registered?
				if ( $this->has_option( $option_name ) ) {
					// get the option
					$option = $this->get_option($option_name);
					// look for option name as POST key
					if ( array_key_exists( $option->name, $_POST ) ) {
						// get new value
						$new_value = $_POST[$option->name];
						// strip slashes from new value?
						if ( is_scalar( $new_value ) ) {
							$new_value = stripslashes( $_POST[$option->name] );
						}
						// update it
						$option->update( $new_value );
					} else {
						// not in POST, delete it
						$option->delete();
					}
					// increment the count
					$save_count++;
				}
			}

			// restore blog
			restore_current_blog();

			// done saving
			return $save_count;

		} else {
			throw new Exception( 'No manifest was rendered' );
		}
	}

	/**
	 * Process the form and generate an AJAX response
	 *
	 * @see process_form
	 */
	public function process_form_ajax()
	{
		// process the form
		$save_count = $this->process_form();

		// any options saved successfuly?
		if ( $save_count == 1 ) {
			Pie_Easy_Ajax::responseStd( true, sprintf( __('%d option successfully updated.', pie_easy_text_domain), $save_count ) );
		} elseif ( $save_count > 1 ) {
			Pie_Easy_Ajax::responseStd( true, sprintf( __('%d options successfully updated.', pie_easy_text_domain), $save_count ) );
		} else {
			Pie_Easy_Ajax::responseStd( false, __('An error has occurred. No options were updated.', pie_easy_text_domain) );
		}
	}

	/**
	 * Export CSS markup from all registered options that have the "css" field type
	 *
	 * @return string|null
	 */
	public function export_css()
	{
		// css to export
		$css = null;

		// loop through and check field type
		foreach ( $this->get_options() as $option ) {
			if ( $option->field_type == Pie_Easy_Options_Option::FIELD_CSS ) {
				// append css markup
				$css .= $option->get() . PHP_EOL;
			}
		}

		return $css;
	}
}

?>
