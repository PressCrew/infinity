<?php
/**
 * PIE API options registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'collections' );

/**
 * Make keeping track of options easy
 */
abstract class Pie_Easy_Options_Registry
{
	/**
	 * The prefix that denotes a section name in the config
	 */
	const SECTION_PREFIX = '~';

	/**
	 * The string on which to split field option key => values
	 */
	const FIELD_OPTION_DELIM = '=';

	/**
	 * The class to use for new sections
	 *
	 * @var Pie_Easy_Options_Section
	 */
	private $section_class;

	/**
	 * The class use for new options
	 * 
	 * @var Pie_Easy_Options_Option 
	 */
	private $option_class;

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
	 * An option renderer instance
	 *
	 * @var Pie_Easy_Options_Renderer
	 */
	private $renderer;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// initiate the maps
		$this->sections = new Pie_Easy_Map();
		$this->options = new Pie_Easy_Map();

		// get the renderer
		$this->renderer = $this->create_renderer();

		if ( !$this->renderer instanceof Pie_Easy_Options_Renderer ) {
			throw new UnexpectedValueException( 'That is not a valid renderer object' );
		}
	}

	/**
	 * Set the class to use for creating new sections
	 *
	 * @param string $class_name
	 * @return boolean
	 */
	private function set_section_class( $class_name )
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
	 * Set the class to use for creating new options
	 *
	 * @param string $class_name
	 * @return boolean
	 */
	private function set_option_class( $class_name )
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
	 * Create a new renderer
	 * 
	 * @return Pie_Easy_Options_Renderer
	 */
	abstract protected function create_renderer();

	/**
	 * Register an option
	 *
	 * @param Pie_Easy_Options_Option $option
	 * @return boolean
	 */
	public function register_option( Pie_Easy_Options_Option $option )
	{
		// make sure that the option has not already been registered
		if ( $this->options->contains( $option->name ) ) {
			throw new Exception( sprintf( 'The "%s" option has already been registered', $option->name ) );
		}

		// register it
		$this->options->add( $option->name, $option );
		return true;
	}

	/**
	 * Add a section
	 *
	 * @param Pie_Easy_Options_Section $section
	 * @return boolean
	 */
	public function register_section( Pie_Easy_Options_Section $section )
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
	 * Check if a section has been registered
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

		throw new Exception( sprintf( 'Unable to get section "%s": not registered.', $section_name ) );
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
	 * Unregister an option
	 *
	 * @param string $option_name
	 * @return boolean
	 */
	public function unregister( $option_name )
	{
		if ( $this->options->contains( $option_name ) ) {
			$this->options->remove( $option_name );
			return true;
		}

		return false;
	}

	/**
	 * Check if an option has been registered
	 *
	 * @param string $option_name
	 * @return boolean
	 */
	public function has_option( $option_name )
	{
		return $this->options->contains( $option_name );
	}

	/**
	 * Return a registered option object
	 *
	 * @param string $option_name
	 * @return Pie_Easy_Options_Option
	 */
	public function get_option( $option_name )
	{
		// check option registry
		if ( $this->options->contains( $option_name ) ) {
			// return it
			return $this->options->item_at( $option_name );
		}

		throw new Exception( sprintf( 'Unable to get option "%s": not registered.', $option_name ) );
	}

	/**
	 * Return all registered options as an array
	 *
	 * @return array
	 */
	public function get_options()
	{
		return $this->options->to_array();
	}

	/**
	 * Load options from an ini file
	 * 
	 * @uses parse_ini_file()
	 * @param string $filename
	 * @param string $section_class
	 * @param string $option_class
	 * @return boolean 
	 */
	public function load_config_file( $filename, $section_class, $option_class )
	{
		// try to set the section and option class
		$this->set_section_class( $section_class );
		$this->set_option_class( $option_class );

		// try to parse the file
		return $this->load_config_array( parse_ini_file( $filename, true ) );
	}

	/**
	 * Load options from an ini string
	 *
	 * @param string $ini_text
	 * @param string $section_class
	 * @param string $option_class
	 * @return boolean
	 */
	public function load_config_text( $ini_text,  $section_class, $option_class )
	{
		// try to set the option class
		$this->set_option_class( $option_class );

		// try to parse the text
		return $this->load_config_array( parse_ini_string( $ini_text, true ) );
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
		// if option has already been registered, the only thing
		// that can be done is to override the default value
		if ( $this->options->contains( $option_name ) ) {
			if ( isset( $option_config['default_value'] ) ) {
				$this->get_option( $option_name )->set_default_value( $option_config['default_value'] );
			}
			return;
		}

		// get section from section registry
		$section = $this->sections->item_at( $option_config['section'] );

		// create new option
		$option = new $this->{option_class}(
			$option_name,
			$option_config['title'],
			$option_config['description'],
			$option_config['field_type'],
			$section->name
		);

		// container class
		if ( isset( $option_config['class'] ) ) {
			$option->set_class( $option_config['class'] );
		}
		
		// default value
		if ( isset( $option_config['default_value'] ) ) {
			$option->set_default_value( $option_config['default_value'] );
		}

		// required feature
		if ( isset( $option_config['required_feature'] ) ) {
			$option->set_required_feature( $option_config['required_feature'] );
		}

		// capabilities
		if ( isset( $option_config['capabilities'] ) ) {
			$option->set_capabilities( $option_config['capabilities'] );
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
						throw new Exception( sprintf( 'The field options callback function "%s" did not return an array.', $callback ) );
					}
				} else {
					throw new Exception( sprintf( 'The field options callback function "%s" does not exist.', $callback ) );
				}

			} else {
				throw new Exception( sprintf( 'The field options for the "%s" option is not configured correctly.', $option_name ) );
			}

			// make sure we ended up with some options
			if ( count( $field_options ) >= 1 ) {
				// finally set them for the option
				$option->set_field_options( $field_options );
			}
		}

		// register it
		return self::register_option( $option );
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

		// register it
		return self::register_section( $section );
	}

	/**
	 * Render one option given it's name
	 *
	 * @param string $option_name
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string
	 */
	public function render_option( $option_name, $output = true )
	{
		if ( $this->options->contains( $option_name) ) {
			return $this->renderer->render( $this->get_option( $option_name ), $output );
		} else {
			throw new Exception( sprintf( 'The "%s" option is not registered.', $option_name ) );
		}
	}

	/**
	 * Render all sections and their registered options and return as one large string
	 *
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string|void
	 */
	public function render_sections( $output = true )
	{
		// make sure there is at least one section to render
		if ( $this->sections->count() < 1 ) {
			throw new Exception( 'There are no registered sections to render.' );
		}

		// make sure there is at least one option to render
		if ( $this->options->count() < 1 ) {
			throw new Exception( 'There are no registered options to render.' );
		}
		
		// the html to return if output is disabled
		$html = '';
		
		// loop through all sections
		foreach ( $this->sections as $section ) {

			// the options markup for this section
			$options_html = '';
			
			// loop through and render each option for this section
			foreach( $this->options as $option ) {
				// assigned to this section?
				if ( $option->section == $section->name ) {
					$options_html .= $this->renderer->render( $option, false );
				}
			}

			// render the section
			$html .= $section->render( $options_html, $output );
			
		}

		$html .= $this->renderer->render_manifest();
		
		// all done
		if ( $output === false ) {
			return $html;
		}
	}

	/**
	 * Look through POST vars for options from this registry that can be saved
	 */
	public function process_form()
	{
		if ( empty( $_POST ) ) {
			return false;
		} elseif ( isset( $_POST['_manifest_'] ) ) {
			// load manifest
			$manifest = explode( ',', $_POST['_manifest_'] );
			// loop through manifest options
			foreach ( $manifest as $option_name ) {
				// is this option registered?
				if ( $this->options->contains( $option_name ) ) {
					// get the option
					$option = $this->get_option($option_name);
					// look for option name as POST key
					if ( array_key_exists( $option->name, $_POST ) ) {
						// yep, update it
						$option->update( $_POST[$option->name] );
					} else {
						// nope, delete it
						$option->delete();
					}
				}
			}
			// done saving
			return true;
		} else {
			throw new Exception( 'No manifest was rendered.' );
		}
	}
}

?>
