<?php
/**
 * PIE API: options factory class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/factory' );

/**
 * Make creating option objects easy
 *
 * @package PIE
 * @subpackage options
 */
class Pie_Easy_Options_Factory extends Pie_Easy_Factory
{
	/**
	 * The string on which to split field option key => values
	 */
	const FIELD_OPTION_DELIM = '=';
	
	/**
	 * Return an instance of an options component
	 *
	 * @param string $theme
	 * @param string $name
	 * @param array $config
	 * @return Pie_Easy_Options_Option
	 */
	public function create( $theme, $name, $config )
	{
		$option = parent::create( $theme, $name, $config );

		// section
		if ( isset( $config['section'] ) ) {
			$option->set_section( $config['section'] );
		}

		// required option
		if ( isset( $config['required_option'] ) ) {
			$option->set_required_option( $config['required_option'] );
		}

		// required feature
		if ( isset( $config['required_feature'] ) ) {
			$option->set_required_feature( $config['required_feature'] );
		}

		// default value
		if ( isset( $config['default_value'] ) ) {
			$option->set_default_value( $config['default_value'], $this->loading_theme );
		}

		// capabilities
		if ( isset( $config['capabilities'] ) ) {
			$option->add_capabilities( $config['capabilities'] );
		}

		// documentation
		if ( isset( $config['documentation'] ) ) {
			$option->set_documentation( $config['documentation'] );
		}

		// css id
		if ( isset( $config['field_id'] ) ) {
			$option->set_field_id( $config['field_id'] );
		}

		// css class
		if ( isset( $config['field_class'] ) ) {
			$option->set_field_class( $config['field_class'] );
		}

		// options
		if ( isset( $config['field_options'] ) ) {

			if ( is_array( $config['field_options'] ) ) {

				// loop through all field options
				foreach ( $config['field_options'] as $field_option ) {
					// split each one at the delimeter
					$field_option = explode( self::FIELD_OPTION_DELIM, $field_option, 2 );
					// add to array
					$field_options[trim($field_option[0])] = trim($field_option[1]);
				}

			} elseif ( strlen( $config['field_options'] ) ) {

				// possibly a function
				$callback = $config['field_options'];

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
				throw new Exception( sprintf( 'The field options for the "%s" option is not configured correctly', $name ) );
			}

			// make sure we ended up with some options
			if ( count( $field_options ) >= 1 ) {
				// finally set them for the option
				$option->set_field_options( $field_options );
			}
		}

		return $option;
	}
}

?>
