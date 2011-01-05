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

Pie_Easy_Loader::load( 'ajax', 'collections' );

/**
 * Make keeping track of options easy
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
	 * Constructor
	 */
	public function __construct()
	{
		// initiate the maps
		$this->sections = new Pie_Easy_Map();
		$this->options = new Pie_Easy_Map();
	}

	/**
	 * Set the class to use for creating new sections
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
	 * Set the class to use for creating new options
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
	 * Set the option renderer
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
	 * Return registered options as an array
	 *
	 * @param Pie_Easy_Options_Section $section Limit options to one section
	 * @return array
	 */
	public function get_options( Pie_Easy_Options_Section $section = null )
	{
		// return options for one section only?
		if ( $section ) {
			// options for this section
			$options = array();
			// loop through and compare names
			foreach ( $this->options as $option ) {
				if ( $section->name == $option->section ) {
					$options[] = $option;
				}
			}
			// return them
			return $options;
		} else {
			// return ALL options
			return $this->options->to_array();
		}
	}

	/**
	 * Return registered options that are valid in a menu
	 *
	 * It does not make sense to list an option in a menu which requires another option,
	 * so this helper method will remove them.
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

		// required option
		if ( isset( $option_config['required_option'] ) ) {
			$option->set_required_option( $option_config['required_option'] );
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
	 * @return string|boolean
	 */
	public function render_option( $option_name, $output = true )
	{
		if ( $this->options->contains( $option_name ) ) {
			
			// get the option from map
			$option = $this->get_option( $option_name );
			
			// render the option
			$html = $this->option_renderer->render( $option, $output );
			
			// render options that require this one
			foreach ( $this->options as $sibling_option ) {
				if ( $option->name == $sibling_option->required_option ) {
					$html .= $this->option_renderer->render( $sibling_option, $output );
				}
			}
			
			// render the manifest
			$html .= $this->option_renderer->render_manifest( $output );
			
			// return result
			return ( $output ) ? true : $html;
		} else {
			throw new Exception( sprintf( 'The "%s" option is not registered.', $option_name ) );
		}
	}

	/**
	 * Look through POST vars for options from this registry that can be saved
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
					// increment the count
					$save_count++;
				}
			}
			
			// done saving
			return $save_count;
			
		} else {
			throw new Exception( 'No manifest was rendered.' );
		}
	}

	/**
	 * Process the form and generate an AJAX response
	 */
	public function process_form_ajax()
	{
		// process the form
		$save_count = $this->process_form();

		// any options saved successfuly?
		if ( $save_count == 1 ) {
			Pie_Easy_Ajax::responseStd( true, sprintf( '%d option successfully updated.', $save_count ) );
		} elseif ( $save_count > 1 ) {
			Pie_Easy_Ajax::responseStd( true, sprintf( '%d options successfully updated.', $save_count ) );
		} else {
			Pie_Easy_Ajax::responseStd( false, 'An error has occurred. No options were updated.' );
		}
	}
}

?>
