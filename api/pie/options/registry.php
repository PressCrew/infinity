<?php
/**
 * PIE Framework API options registry
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

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
	 * All sections that are currently configured
	 *
	 * @var array
	 */
	private $sections = array();

	/**
	 * All options that are currently configured
	 *
	 * @var array
	 */
	private $options = array();
	
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
		// get the renderer
		$this->renderer = $this->create_renderer();

		if ( !$this->renderer instanceof Pie_Easy_Options_Renderer ) {
			throw new UnexpectedValueException( 'That is not a valid renderer object' );
		}
	}

	/**
	 * Create a new option from scratch
	 *
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @param string $desc
	 * @param string $field_type
	 * @param string $section
	 * @return Pie_Easy_Options_Option
	 */
	abstract protected function create_option( $name, $title, $desc, $field_type, $section = self::DEFAULT_SECTION );

	/**
	 * Create a new section from scratch
	 *
	 * @param string $name Section name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @return Pie_Easy_Options_Section
	 */
	abstract protected function create_section( $name, $title );

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
	public function register( Pie_Easy_Options_Option $option )
	{
		// make sure that the option has not already been registered
		if ( $this->registered( $option->name ) ) {
			throw new Exception( sprintf( 'The "%s" option has already been registered', $option->name ) );
		}

		// register it
		$this->options[$option->name] = $option;
		return true;
	}

	/**
	 * Add a section
	 *
	 * @param Pie_Easy_Options_Section $section
	 * @return boolean
	 */
	public function add_section( Pie_Easy_Options_Section $section )
	{
		// make sure that the section has not already been registered
		if ( array_key_exists( $section->name, $this->sections ) ) {
			throw new Exception( sprintf( 'The "%s" section has already been registered', $section->name ) );
		}

		// register it
		$this->sections[$section->name] = $section;
		return true;
	}

	/**
	 * Unregister an option
	 *
	 * @param string $option_name
	 * @return boolean
	 */
	public function unregister( $option_name )
	{
		if ( $this->registered( $option_name ) ) {
			unset( $this->options[$option_name] );
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
	public function registered( $option_name )
	{
		return array_key_exists( $option_name, $this->options );
	}

	/**
	 * Return a registered option object
	 *
	 * @param string $option_name
	 * @return Pie_Easy_Options_Option
	 */
	public function option( $option_name )
	{
		if ( $this->registered( $option_name ) ) {
			return $this->options[$option_name];
		}

		throw new Exception( sprintf( 'Unable to get option "%s": not registered.', $option_name ) );
	}

	/**
	 * Load options from an ini file
	 * 
	 * @uses parse_ini_file()
	 * @param string $filename
	 * @return boolean 
	 */
	public function load_config_file( $filename )
	{
		// try to parse the file
		return $this->load_config_array( parse_ini_file( $filename, true ) );
	}

	/**
	 * Load options from an ini string
	 *
	 * @param string $ini_text
	 * @return boolean
	 */
	public function load_config_text( $ini_text )
	{
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
	 * @param string $option_name
	 * @param string $option_config
	 * @return boolean
	 */
	private function load_config_option( $option_name, $option_config )
	{
		// create new option
		$option = $this->create_option(
			$option_name,
			$option_config['title'],
			$option_config['description'],
			$option_config['field_type'],
			$option_config['section']
		);

		// container class
		if ( isset( $option_config['class'] ) ) {
			$option->set_class( $option_config['class'] );
		}
		
		// default value
		if ( isset( $option_config['default_value'] ) ) {
			$option->set_default_value( $option_config['default_value'] );
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
		if ( isset( $option_config['field_options'] ) &&
			 is_array( $option_config['field_options'] ) &&
			 count( $option_config['field_options'] ) >= 1 )
		{
			// the final field options
			$field_options = array();

			// loop through all field options
			foreach ( $option_config['field_options'] as $field_option ) {
				// split each one at the delimeter
				$field_option = explode( self::FIELD_OPTION_DELIM, $field_option, 2 );
				// add to array
				$field_options[trim($field_option[0])] = trim($field_option[1]);
			}

			// finally set them for the option
			$option->set_field_options( $field_options );
		}

		// register it
		return self::register( $option );
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
		$section = $this->create_section(
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
		return self::add_section( $section );
	}

	/**
	 * Render one option given it's name
	 *
	 * @param string $option_name
	 * @param boolean $output Set to false to return results instead of printing
	 * @return string
	 */
	public function render( $option_name, $output = true )
	{
		if ( $this->registered( $option_name) ) {
			return $this->renderer->render( $this->option( $option_name ), $output );
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
		if ( empty( $this->sections ) ) {
			throw new Exception( 'There are no registered sections to render.' );
		}

		// make sure there is at least one option to render
		if ( empty( $this->options ) ) {
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
		
		// all done
		if ( $output === false ) {
			return $html;
		}
	}
}

?>
