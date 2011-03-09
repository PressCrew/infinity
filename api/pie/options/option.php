<?php
/**
 * PIE API options option class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package pie
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'collections', 'docs', 'schemes' );

/**
 * Make an option easy
 *
 * @property-read string $section
 * @property-read string $title
 * @property-read string $description
 * @property string $class
 * @property string $field_id
 * @property string $field_class
 * @property-read string $field_type Type of field to generate for the option
 * @property-read array $field_options An array of field options
 * @property array $capabilities Required capabilities, can only be appended
 * @property mixed $default_value Default value of the option
 * @property boolean|string $documentation true/false to enable/disable, string for manual page name
 * @property-read string $required_option Sibling option required for this option to display
 * @property-read string $required_feature Feature required for this option to display
 */
abstract class Pie_Easy_Options_Option
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
	 * Name of the default section
	 */
	const DEFAULT_SECTION = 'default';
	
	/**
	 * Field types
	 */
	const FIELD_CATEGORY = 'category';
	const FIELD_CATEGORIES = 'categories';
	const FIELD_CHECKBOX = 'checkbox';
	const FIELD_COLORPICKER = 'colorpicker';
	const FIELD_CSS = 'css';
	const FIELD_PAGE = 'page';
	const FIELD_PAGES = 'pages';
	const FIELD_POST = 'post';
	const FIELD_POSTS = 'posts';
	const FIELD_RADIO = 'radio';
	const FIELD_SELECT = 'select';
	const FIELD_TAG = 'tag';
	const FIELD_TAGS = 'tags';
	const FIELD_TEXT = 'text';
	const FIELD_TEXTAREA = 'textarea';
	const FIELD_TEXTBLOCK = 'textblock';
	const FIELD_UPLOAD = 'upload';

	/**
	 * The theme that created this option
	 * 
	 * @var string 
	 */
	private $theme;
	
	/**
	 * Name of the option
	 *
	 * @var string
	 */
	private $name;

	/**
	 * @var Pie_Easy_Map Option directives
	 */
	private $directives;

	/**
	 * Name of the theme which last over wrote the default value
	 *
	 * @var string
	 */
	private $default_value_theme;

	/**
	 * If true, a POST value will override the real option value
	 * 
	 * @var boolean
	 */
	private $post_override = false;

	/**
	 * Constructor
	 * 
	 * @param string $theme The theme that created this option
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @param string $desc
	 * @param string $field_type
	 * @param string $section
	 */
	final public function __construct( $theme, $name, $title, $desc, $field_type, $section = self::DEFAULT_SECTION )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+(_[a-z0-9]+)*$/', $name ) ) {
			$this->name = $name;
		} else {
			throw new Exception( 'Option name does not match the allowed pattern.' );
		}

		// init directives map
		$this->directives = new Pie_Easy_Map();
		
		// set basic string properties
		$this->theme = $theme;

		// set directives
		$this->set_directive( 'section', $section, true );
		$this->set_directive( 'title', $title, true );
		$this->set_directive( 'description', $desc, true );
		$this->add_capabilities( 'manage_options' );
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
		if ( $this->directives->contains($name) ) {
			return $this->directives->item_at($name)->value;
		} else {
			return $this->$name;
		}
	}

	/**
	 * Toggle post override ON
	 */
	public function enable_post_override()
	{
		$this->post_override = true;
	}

	/**
	 * Toggle post override OFF
	 */
	public function disable_post_override()
	{
		$this->post_override = false;
	}

	/**
	 * Get (read) the value of this option
	 *
	 * @uses get_option()
	 * @return mixed
	 */
	public function get()
	{
		if ( $this->post_override === true && isset( $_POST[$this->name] ) ) {
			return $_POST[$this->name];
		} else {
			return get_option( $this->get_api_name(), $this->default_value );
		}
	}

	/**
	 * Get special meta data about an option itself
	 *
	 * @param string $type
	 * @return mixed
	 */
	public function get_meta( $type )
	{
		return get_option( $this->get_meta_option_name( $type ) );
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
	 * Delete this option
	 *
	 * @uses delete_option()
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
	 * Get the attachment image source
	 *
	 * @param string $size
	 * @return string|false
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
			// determine theme to use
			if ( $attach_id == $this->default_value ) {
				$theme = $this->default_value_theme;
			} else {
				$theme = $this->theme;
			}
			// mimic the src array
			$src[0] = Pie_Easy_Scheme::instance()->theme_file_url( $theme, $attach_id );
			$src[1] = null;
			$src[2] = null;
		}

		// did we find one?
		if ( is_array($src) ) {
			return $src;
		} else {
			return false;
		}
	}

	/**
	 * Return the URL of an image attachment
	 *
	 * @param string $size
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

			// determine theme to use
			if ( $value == $this->default_value ) {
				$theme = $this->default_value_theme;
			} else {
				$theme = $this->theme;
			}

			// they must have provided an image path
			return Pie_Easy_Scheme::instance()->theme_file_url( $theme, $value );

		}

		return null;
	}

	/**
	 * Check that user has all required capabilities to edit this option
	 *
	 * @return boolean
	 */
	final public function check_caps()
	{
		foreach ( $this->capabilities as $cap ) {
			if ( !current_user_can( $cap ) ) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Check that theme has required feature support enabled if applicable
	 *
	 * @return boolean
	 */
	final public function supported()
	{
		if ( $this->required_feature ) {
			return current_theme_supports( $this->required_feature );
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
		switch ( $type ) {
			case self::FIELD_CATEGORY:
			case self::FIELD_CATEGORIES:
			case self::FIELD_CSS:
			case self::FIELD_CHECKBOX:
			case self::FIELD_COLORPICKER:
			case self::FIELD_PAGE:
			case self::FIELD_PAGES:
			case self::FIELD_POST:
			case self::FIELD_POSTS:
			case self::FIELD_RADIO:
			case self::FIELD_SELECT:
			case self::FIELD_TAG:
			case self::FIELD_TAGS:
			case self::FIELD_TEXT:
			case self::FIELD_TEXTAREA:
			case self::FIELD_TEXTBLOCK:
			case self::FIELD_UPLOAD:
				return true;
			default:
				return false;
		}
	}

	/**
	 * Set a directive
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $read_only
	 */
	private function set_directive( $name, $value, $read_only = null )
	{
		if ( $this->directives->contains($name) ) {
			$this->directives->item_at($name)->set_value($value, true);
		} else {
			$directive = new Pie_Easy_Options_Directive( $name, $value, $read_only );
			$this->directives->add( $name, $directive );
		}
	}

	/**
	 * Set the container css class
	 *
	 * @param string $class
	 */
	public function set_class( $class )
	{
		$this->set_directive( 'class', $class );
	}

	/**
	 * Set the field type
	 * 
	 * @param string $type
	 * @return boolean
	 */
	private function set_field_type( $type )
	{
		if ( $this->check_field_type( $type ) ) {
			// set it
			$this->set_directive( 'field_type', $type, true );
			// add cap for upload field
			if ( 'upload' == $type ) {
				$this->add_capabilities( 'upload_files' );
			}
			// done
			return true;
		} else {
			throw new Exception( sprintf( 'The "%s" field type is not valid.', $type ) );
		}
	}

	/**
	 * Set the field css id
	 *
	 * @param string $field_id
	 */
	public function set_field_id( $field_id )
	{
		$this->set_directive( 'field_id', $field_id );
	}

	/**
	 * Set the field css class
	 *
	 * @param string $field_class
	 */
	public function set_field_class( $field_class )
	{
		$this->set_directive( 'field_class', $field_class );
	}

	/**
	 * Set the field options
	 * 
	 * @param array $field_options
	 */
	public function set_field_options( $field_options )
	{
		$this->set_directive( 'field_options', $field_options, true );
	}

	/**
	 * Set the required option
	 *
	 * @param string $option_name
	 */
	public function set_required_option( $option_name )
	{
		$this->set_directive( 'required_option', $option_name, true );
	}

	/**
	 * Set the required feature
	 *
	 * @param string $feature_name
	 */
	public function set_required_feature( $feature_name )
	{
		$this->set_directive( 'required_feature', $feature_name, true );
	}

	/**
	 * Set default value
	 *
	 * @param mixed $value
	 * @param string $theme The theme that last update the default value
	 * @return true
	 */
	public function set_default_value( $value, $theme = null )
	{
		// use option's theme if empty
		$this->default_value_theme = empty( $theme ) ? $this->theme : $theme;

		// set the default value
		$this->set_directive( 'default_value', $value );

		return true;
	}

	/**
	 * Set additional capabilities
	 *
	 * @param string $string A comma separated list of capabilities
	 */
	public function add_capabilities( $string )
	{
		// split at comma
		$caps = explode( ',', $string );

		// trim and set each
		foreach ( $caps as $cap ) {
			$cap_trimmed = trim( $cap );
			$capabilities[$cap_trimmed] = $cap_trimmed;
		}

		if ( $this->capabilities ) {
			$capabilities = array_merge( $this->capabilities, $capabilities );
		}

		$this->set_directive( 'capabilities', $capabilities );
	}

	/**
	 * Set the documentation file
	 *
	 * @param string $rel_path Path to documentation file relative to the theme config docs
	 */
	public function set_documentation( $rel_path )
	{
		$this->set_directive( 'documentation', trim( $rel_path, '\\/' ) );
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
