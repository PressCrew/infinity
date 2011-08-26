<?php
/**
 * PIE API: options option class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base', 'collections', 'utils/docs', 'schemes' );

/**
 * Interface to implement if the option defines its own field_options internally
 */
interface Pie_Easy_Options_Option_Auto_Field
{
	/**
	 * @return array of field options in [value] => [description] format
	 */
	public function load_field_options();
}

/**
 * Interface to implement if the option is storing an image attachment id
 */
interface Pie_Easy_Options_Option_Attachment_Image
{
	/**
	 * @return array attachment meta data
	 */
	public function get_image_src( $size = 'thumbnail', $attach_id = null );
	/**
	 * @return string absolute URL to image file
	 */
	public function get_image_url( $size = 'thumbnail' );
}

/**
 * Make an option easy
 *
 * @package PIE
 * @subpackage options
 * @property-read string $section The section to which this options is assigned (slug)
 * @property-read string $field_id The CSS id to apply to the option's input field
 * @property-read string $field_class The CSS class to apply to the option's input field
 * @property-read array $field_options An array of field options
 * @property-read $default_value Default value of the option
 */
abstract class Pie_Easy_Options_Option extends Pie_Easy_Component
{
	/**
	 * All global options are prepended with this prefix template
	 */
	const PREFIX_TPL = '%s_opt_';

	/**
	 * Special meta options use this as a delimeter
	 */
	const META_DELIM = '.';

	/**
	 * For tracking the time updated
	 */
	const META_TIME_UPDATED = 'time_updated';

	/**
	 * If true, a POST value will override the real option value
	 *
	 * @var boolean
	 */
	private $__post_override__ = false;

	/**
	 * @ignore
	 */
	public function init()
	{
		parent::init();

		// user must be allowed to manage options
		$this->add_capabilities( 'manage_options' );

		// special cases
		if ( $this instanceof Pie_Easy_Options_Option_Auto_Field ) {
			$this->field_options = $this->load_field_options();
		}
	}

	/**
	 * Check that theme has required feature support enabled if applicable
	 *
	 * @todo The logic here is suspicious?
	 * @todo Make required feature available to all components?
	 * @return boolean
	 */
	public function supported()
	{
		if ( $this->required_feature ) {
			return current_theme_supports( $this->required_feature );
		}

		return parent::supported();
	}

	/**
	 * Render this option AND its required siblings
	 *
	 * @param boolean $output Whether to output or return result
	 * @return string|void
	 */
	public function render( $output = true )
	{
		// render myself first
		$html = parent::render( $output );

		// render options that require this one
		foreach ( $this->policy()->registry()->get_siblings($this) as $sibling_option ) {
			$html .= $sibling_option->render( $output );
		}

		// return result
		return ( $output ) ? true : $html;
	}

	/**
	 * This method must be implemented to print the option's field HTML
	 */
	abstract public function render_field();

	/**
	 * Toggle post override ON
	 *
	 * If enabled, post override will force the option to return it's value as set in POST
	 *
	 * @see disable_post_override
	 */
	public function enable_post_override()
	{
		$this->__post_override__ = true;
	}

	/**
	 * Toggle post override OFF
	 *
	 * @see enable_post_override
	 */
	public function disable_post_override()
	{
		$this->__post_override__ = false;
	}

	/**
	 * Get (read) the value of this option
	 *
	 * @see enable_post_override
	 * @return mixed
	 */
	public function get()
	{
		if ( $this->__post_override__ === true && isset( $_POST[$this->name] ) ) {
			return $_POST[$this->name];
		} else {
			return get_option( $this->get_api_name(), $this->default_value );
		}
	}

	/**
	 * Get special meta data about an option itself
	 *
	 * @param string $type The available types are constants of this class prefixed with "META_"
	 * @return mixed
	 */
	public function get_meta( $type )
	{
		return get_option( $this->get_meta_option_name( $type ) );
	}

	/**
	 * Update the value of this option
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	public function update( $value )
	{
		if ( $this->check_caps() ) {
			// force numeric values to integers
			if ( is_numeric( $value ) ) {
				$value = (integer) $value;
			}
			// is the value null, an empty string, or equal to the default value?
			if ( $value === null || $value === '' || $value === $this->default_value ) {
				// its pointless to store this option
				// try to delete it in case it already exists
				return $this->delete();
			} else {
				// create or update it
				if ( update_option( $this->get_api_name(), $value ) ) {
					$this->update_meta( self::META_TIME_UPDATED, time() );
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Set special meta data about an option itself
	 *
	 * @param string $type
	 * @param mixed $value
	 * @return boolean
	 */
	private function update_meta( $type, $value )
	{
		return update_option( $this->get_meta_option_name( $type ), $value );
	}

	/**
	 * Delete this option completely from the database
	 *
	 * @return boolean
	 */
	public function delete()
	{
		if ( $this->check_caps() ) {
			if ( delete_option( $this->get_api_name() ) ) {
				$this->delete_meta();
				return true;
			}
		}

		return false;
	}

	/**
	 * Delete all special meta data about an option
	 *
	 * @return boolean
	 */
	private function delete_meta()
	{
		return delete_option( $this->get_meta_option_name( self::META_TIME_UPDATED ) );
	}

	/**
	 * Set the section
	 *
	 * @param string $section
	 */
	public function set_section( $section )
	{
		// lookup the section registry
		$section_registry = Pie_Easy_Policy::instance('Pie_Easy_Sections_Policy')->registry();

		// get section from section registry
		$section = $section_registry->get( $section );

		// adding options to parent sections is not allowed
		foreach ( $section_registry->get_all() as $section_i ) {
			if ( $section->is_parent_of( $section_i ) ) {
				throw new Exception(
					sprintf( 'Cannot add options to section "%s" because it is acting as a parent section', $section->name ) );
			}
		}

		$this->directives()->set( $this->theme, 'section', $section->name );
	}

	/**
	 * Set the CSS id attribute to apply to the field of this option
	 *
	 * @param string $field_id
	 */
	public function set_field_id( $field_id )
	{
		$this->directives()->set( $this->theme, 'field_id', $field_id );
	}

	/**
	 * Set the CSS class attribute to apply to the field of this option
	 *
	 * @param string $field_class
	 */
	public function set_field_class( $field_class )
	{
		$this->directives()->set( $this->theme, 'field_class', $field_class );
	}

	/**
	 * Set the field options for this option
	 *
	 * @param array $field_options
	 */
	public function set_field_options( $field_options )
	{
		if ( $this instanceof Pie_Easy_Options_Option_Auto_Field ) {
			throw new Exception( 'Cannot set field options for an auto field option.' );
		} else {
			$this->directives()->set( $this->theme, 'field_options', $field_options, true );
		}
	}

	/**
	 * Set the default value to be used by this option in the event it has not been set
	 *
	 * @param mixed $value New value
	 * @param string $theme The theme that last update the default value
	 * @return true
	 */
	public function set_default_value( $value, $theme = null )
	{
		// set the default value
		$this->directives()->set( $this->theme, 'default_value', $value );

		return true;
	}

	/**
	 * Build a special meta option name based on the given type
	 *
	 * @param string $type
	 * @return string
	 */
	private function get_meta_option_name( $type )
	{
		switch ( $type ) {
			case self::META_TIME_UPDATED:
				return $this->get_api_name() . self::META_DELIM . $type;
			default:
				throw new Exception( sprintf( 'The "%s" type is not valid', $type ) );
		}
	}

	/**
	 * Get the prefix for API option
	 *
	 * @return string
	 */
	private function get_api_prefix()
	{
		return sprintf( self::PREFIX_TPL, $this->policy()->get_api_slug() );
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
}

/**
 * An option for storing an image (via WordPress attachment API)
 */
abstract class Pie_Easy_Options_Option_Image
	extends Pie_Easy_Options_Option
		implements Pie_Easy_Options_Option_Attachment_Image
{
	/**
	 * Get the attachment image source details
	 *
	 * Returns an array with attachment details
	 *
	 * <code>
	 * Array (
	 *   [0] => url
	 *   [1] => width
	 *   [2] => height
	 * )
	 * </code>
	 *
	 * @see wp_get_attachment_image_src()
	 * @link http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
	 * @param string $size Either a string (`thumbnail`, `medium`, `large` or `full`), or a two item array representing width and height in pixels, e.g. array(32,32). The size of media icons are never affected.
	 * @param integer $attach_id The id of the attachment, defaults to option value.
	 * @return array|false
	 */
	public function get_image_src( $size = 'thumbnail', $attach_id = null )
	{
		// attach id was passed?
		if ( empty( $attach_id ) ) {
			$attach_id = $this->get();
		}

		if ( is_numeric( $attach_id ) ) {
			// try to get the attachment info
			$src = wp_get_attachment_image_src( $attach_id, $size );
		} else {
			// use default
			$directive = $this->directives()->get( 'default_value' );
			// mimic the src array
			$src = array_fill( 0, 3, null );
			// is a default set?
			if ( $directive->value ) {
				$src[0] = Pie_Easy_Files::theme_file_url( $directive->theme, $directive->value );
			}
		}

		// did we find one?
		if ( is_array($src) ) {
			return $src;
		} else {
			return false;
		}
	}

	/**
	 * Return the URL of an image attachment for this option
	 *
	 * This method is only useful if the option is storing the id of an attachment
	 *
	 * @param string $size Either a string (`thumbnail`, `medium`, `large` or `full`), or a two item array representing width and height in pixels, e.g. array(32,32). The size of media icons are never affected.
	 * @return string|false
	 */
	public function get_image_url( $size = 'thumbnail' )
	{
		// get the value
		$value = $this->get();

		// did we get a number?
		if ( is_numeric( $value ) && $value >= 1 ) {

			// get the details
			$src = $this->get_image_src( $size, $value );

			// try to return a url
			return ( $src ) ? $src[0] : false;

		} elseif ( is_string( $value ) && strlen( $value ) >= 1 ) {

			// use default
			$directive = $this->directives()->get( 'default_value' );

			// they must have provided an image path
			return Pie_Easy_Files::theme_file_url( $directive->theme, $directive->value );

		}

		return null;
	}
}

?>
