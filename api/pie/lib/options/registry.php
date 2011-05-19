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

Pie_Easy_Loader::load( 'base/registry', 'options/factory', 'utils/ajax' );

/**
 * Make keeping track of options easy
 *
 * @package PIE
 * @subpackage options
 */
abstract class Pie_Easy_Options_Registry extends Pie_Easy_Registry
{
	/**
	 * The string on which to split field option key => values
	 */
	const FIELD_OPTION_DELIM = '=';

	/**
	 * Init ajax requirements
	 */
	public function init_ajax()
	{
		// call parent
		parent::init_ajax();
		
		// init ajax for each registered option
		foreach ( $this->get_all() as $option ) {
			$option->init_ajax();
		}
	}
	
	/**
	 * Init screen dependencies for all applicable options to be rendered
	 */
	public function init_screen()
	{
		// call parent
		parent::init_screen();

		// init screen for each registered option
		foreach ( $this->get_all() as $option ) {
			$option->init_screen();
		}
	}

	/**
	 * Enqueue required styles
	 */
	public function init_styles()
	{
		// call parent
		parent::init_styles();
		
		// init styles for each registered option
		foreach ( $this->get_all() as $option ) {
			$option->init_styles();
		}
	}

	/**
	 * Enqueue required scripts
	 */
	public function init_scripts()
	{
		// call parent
		parent::init_scripts();

		// jQuery UI is always needed
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-progressbar' );
		wp_enqueue_script( 'jquery-ui-tabs' );

		// init scripts for each registered option
		foreach ( $this->get_all() as $option ) {
			$option->init_scripts();
		}

		// call localize script *LAST*
		$this->localize_script();
	}

	/**
	 * Template method to allow localization of scripts
	 */
	protected function localize_script()
	{
		// override this to apply special localizations that apply to your implementation
	}

	/**
	 * Return sibling options as an array
	 *
	 * @param Pie_Easy_Options_Option $option
	 * @return array
	 */
	public function get_siblings( Pie_Easy_Options_Option $option )
	{
		// options to return
		$options = array();

		// render options that require this one
		foreach ( $this->get_all() as $sibling_option ) {
			if ( $option->name == $sibling_option->required_option ) {
				$options[] = $sibling_option;
			}
		}

		return $options;
	}

	/**
	 * Return registered options as an array
	 *
	 * @param Pie_Easy_Sections_Section $section Limit options to one section by passing a section object
	 * @return array
	 */
	public function get_for_section( Pie_Easy_Sections_Section $section )
	{
		// options to return
		$options = array();

		// loop through and compare names
		foreach ( parent::get_all() as $option ) {

			// do section names match?
			if ( $section->name != $option->section ) {
				continue;
			}

			// add to array
			$options[] = $option;
		}

		// return them
		return $options;
	}

	/**
	 * Return registered options that are valid in a menu
	 *
	 * It does not make sense to list an option in a menu which requires another option,
	 * so this helper method will return an array without them.
	 *
	 * @param Pie_Easy_Sections_Section $section Limit options to one section
	 * @return array
	 */
	public function get_menu_options( Pie_Easy_Sections_Section $section = null )
	{
		// get all options for section
		$options = $this->get_for_section( $section );

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
	 * Load a single option into the registry (one parsed ini section)
	 *
	 * @param string $option_name
	 * @param array $option_config
	 * @return boolean
	 */
	final protected function load_config_single( $option_name, $option_config )
	{
		// if option has already been registered, deep copy that option
		// and possibly override some values
		if ( $this->has( $option_name ) ) {

			// get source option, which is on top of the options stack
			$source_option = $this->get_stack($option_name)->peek();

			// get class of source option
			$source_class = get_class( $source_option );

			// copy option properties (do NOT use cloning)
			$option = new $source_class(
				$this->loading_theme,
				$source_option->name,
				$source_option->title,
				$source_option->description,
				$source_option->section
			);

		} else {

			// lookup the section registry
			$section_registry = $section = Pie_Easy_Policy::instance('Pie_Easy_Sections_Policy')->registry();

			// get section from section registry
			$section = $section_registry->get( $option_config['section'] );

			// adding options to parent sections is not allowed
			foreach ( $section_registry->get_all() as $section_i ) {
				if ( $section->is_parent_of( $section_i ) ) {
					throw new Exception(
						sprintf( 'Cannot add options to section "%s" because it is acting as a parent section', $section->name ) );
				}
			}

			// create new option
			$option = $this->policy()->factory()->create(
				$option_config['field_type'],
				$this->loading_theme,
				$option_name,
				$option_config['title'],
				$option_config['description'],
				$option_config['section']
			);

		}

		// register it
		$this->register( $option );

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
				if ( $this->has( $option_name ) ) {
					// get the option
					$option = $this->get($option_name);
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
		foreach ( $this->get_all() as $option ) {
			if ( $option instanceof Pie_Easy_Exts_Option_Css ) {
				// append css markup
				$css .= $option->get() . PHP_EOL;
			}
		}

		return $css;
	}
}

?>
