<?php
/**
 * PIE Framework API options option class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

/**
 * Make an option easy
 */
abstract class Pie_Easy_Options_Option
{
	/**
	 * All options are prepended with this prefix template
	 */
	const PREFIX_TPL = '%s_opt_';

	/**
	 * Name of the default section
	 */
	const DEFAULT_SECTION = 'default';

	/**
	 * Section of the option
	 *
	 * @var string
	 */
	private $section;

	/**
	 * Name of the option
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Title of the option
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Description of the option
	 *
	 * @var string
	 */
	private $description;

	/**
	 * The class of the option container
	 *
	 * @var string
	 */
	private $class;

	/**
	 * CSS id of the option form field
	 *
	 * @var string
	 */
	private $field_id;

	/**
	 * CSS id of the option form field
	 *
	 * @var string
	 */
	private $field_class;

	/**
	 * Type of field to generate for the option
	 *
	 * @var string
	 */
	private $field_type;

	/**
	 * Valid field types
	 *
	 * @var array
	 */
	private $field_types = array(
		'category', 'categories', 'checkbox',
		'colorpicker', 'page', 'pages', 'post', 'posts',
		'radio', 'select', 'tag', 'tags', 'text',
		'textarea', 'textblock', 'upload' );

	/**
	 * An array of field options
	 *
	 * @var array
	 */
	private $field_options = array();

	/**
	 * Default value of the option
	 *
	 * @var mixed
	 */
	private $default_value;

	/**
	 * Required capabilities
	 *
	 * @var array
	 */
	private $capabilities = array( 'manage_options' );

	/**
	 * Constructor
	 * 
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @param string $desc
	 * @param string $field_type
	 * @param string $section
	 */
	public function __construct( $name, $title, $desc, $field_type, $section = self::DEFAULT_SECTION )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+(_[a-z0-9]+)*$/', $name ) ) {
			$this->name = $name;
		} else {
			throw new Exception( 'Option name does not match the allowed pattern.' );
		}

		// set basic string properties
		$this->section = $section;
		$this->title = $title;
		$this->description = $desc;

		// set the field type
		$this->set_field_type( $field_type );
	}

	/**
	 * Allow read access to all properties (for now)
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		return $this->$name;
	}

	/**
	 * Get (read) the value of this option
	 *
	 * @uses get_option()
	 * @return mixed
	 */
	public function get()
	{
		return get_option( $this->get_api_name(), $this->default_value );
	}

	/**
	 * Update the value of this option
	 *
	 * @uses update_option()
	 * @param mixed $value
	 * @return boolean
	 */
	public function update( $value )
	{
		if ( $this->check_caps() ) {
			return update_option( $this->get_api_name(), $value );
		}
		
		return false;
	}

	/**
	 * Delete this option
	 *
	 * @uses delete_option()
	 * @return boolean
	 */
	public function delete()
	{
		if ( $this->check_caps() ) {
			return delete_option( $this->get_api_name() );
		}

		return false;
	}

	/**
	 * Check that user has all required capabilities to edit this option
	 *
	 * @return boolean
	 */
	private function check_caps()
	{
		foreach ( $this->capabilities as $cap ) {
			if ( !current_user_can( $cap ) ) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Check if field type is valid
	 * 
	 * @param string $type
	 * @return boolean
	 */
	private function check_field_type( $type )
	{
		return in_array( $type, $this->field_types, true );
	}

	/**
	 * Set the container css class
	 *
	 * @param string $class
	 */
	public function set_class( $class )
	{
		if ( empty( $this->class ) ) {
			$this->class = $class;
		} else {
			throw new Exception( sprintf( 'The container class for "%s" has already been set.', $this->name ) );
		}
	}

	/**
	 * Set the field type
	 * 
	 * @param string $type
	 * @return boolean
	 */
	private function set_field_type( $type )
	{
		if ( empty( $this->field_type ) ) {
			if ( $this->check_field_type( $type ) ) {
				// set it
				$this->field_type = $type;
				// add cap for upload field
				if ( 'upload' == $type ) {
					$this->capabilities['upload_files'] = 'upload_files';
				}
				// done
				return true;
			} else {
				throw new Exception( sprintf( 'The "%s" field type is not valid.', $type ) );
			}
		} else {
			throw new Exception( sprintf( 'The field type for "%s" has already been set.', $this->name ) );
		}
	}

	/**
	 * Set the field css id
	 *
	 * @param string $field_id
	 */
	public function set_field_id( $field_id )
	{
		if ( empty( $this->field_id ) ) {
			$this->field_id = $field_id;
		} else {
			throw new Exception( sprintf( 'The field id for "%s" has already been set.', $this->name ) );
		}
	}

	/**
	 * Set the field css class
	 *
	 * @param string $field_class
	 */
	public function set_field_class( $field_class )
	{
		if ( empty( $this->field_class ) ) {
			$this->field_class = $field_class;
		} else {
			throw new Exception( sprintf( 'The field class for "%s" has already been set.', $this->name ) );
		}
	}

	/**
	 * Set the field options
	 * 
	 * @param array $field_options
	 */
	public function set_field_options( $field_options )
	{
		if ( empty( $this->field_options ) ) {
			$this->field_options= $field_options;
		} else {
			throw new Exception( sprintf( 'The field options for "%s" have already been set.', $this->name ) );
		}
	}

	/**
	 * Set default value
	 *
	 * @param mixed $value
	 */
	public function set_default_value( $value )
	{
		if ( empty( $this->default_value ) ) {
			$this->default_value = $value;
		} else {
			throw new Exception( sprintf( 'The default value for "%s" has already been set.', $this->name ) );
		}
	}

	/**
	 * Set additional capabilities
	 *
	 * @param string $string A comma separated list of capabilities
	 */
	public function set_capabilities( $string )
	{
		if ( empty( $this->capabilities ) ) {
			// split at comma
			$caps = explode( ',', $string );
			// trim and set each
			foreach ( $caps as $cap ) {
				$cap_trimmed = trim( $cap );
				$this->capabilities[$cap_trimmed] = $cap_trimmed;
			}
		} else {
			throw new Exception( sprintf( 'The capabilities for "%s" has already been set.', $this->name ) );
		}
	}

	/**
	 * Get the prefix for API option
	 *
	 * @return string
	 */
	private function get_api_prefix()
	{
		return sprintf( self::PREFIX_TPL, $this->get_api_slug() );
	}

	/**
	 * Get the full name for API option
	 *
	 * @return string
	 */
	private function get_api_name()
	{
		return $this->get_api_prefix() . $this->name;
	}

	/**
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	abstract protected function get_api_slug();

}

?>
