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

Pie_Easy_Loader::load( 'collections', 'utils/docs', 'schemes' );

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
 * This object is passed to each option allowing the implementing API to
 * customize a few specific varaibles.
 */
abstract class Pie_Easy_Options_Option_Conf
{
	/**
	 * Return the name of the implementing API
	 *
	 * If you are rolling your own parent theme, this should probably return the
	 * name of the theme you are creating.
	 *
	 * @return string
	 */
	abstract public function get_api_slug();

	/**
	 * Return an instance of the options uploader class to be used
	 *
	 * @return Pie_Easy_Options_Uploader
	 */
	abstract public function get_options_uploader();
}

/**
 * Make an option easy
 *
 * @package PIE
 * @subpackage options
 * @property-read Pie_Easy_Options_Option_Conf
 * @property-read string $section The section to which this options is assigned (slug)
 * @property-read string $title The option title
 * @property-read string $description The option description
 * @property string $class The CSS class to apply to the option's container
 * @property string $field_id The CSS id to apply to the option's input field
 * @property string $field_class The CSS class to apply to the option's input field
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
	 * The option configuration instance
	 * 
	 * @var Pie_Easy_Options_Option_Conf
	 */
	private $conf;

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
	 * @param Pie_Easy_Options_Option_Conf $conf The option configuration object
	 * @param string $theme The theme that created this option
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @param string $desc
	 * @param string $section
	 */
	final public function __construct( Pie_Easy_Options_Option_Conf $conf, $theme, $name, $title, $desc, $section = self::DEFAULT_SECTION  )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+(_[a-z0-9]+)*$/', $name ) ) {
			$this->name = $name;
		} else {
			throw new Exception( 'Option name does not match the allowed pattern' );
		}

		// init directives map
		$this->directives = new Pie_Easy_Map();

		// set basic properties
		$this->conf = $conf;
		$this->theme = $theme;

		// set directives
		$this->set_directive( 'section', $section, true );
		$this->set_directive( 'title', $title, true );
		$this->set_directive( 'description', $desc, true );
		$this->add_capabilities( 'manage_options' );

		// special cases
		if ( $this instanceof Pie_Easy_Options_Option_Auto_Field ) {
			$this->field_options = $this->load_field_options();
		}

		// run init
		$this->init();
	}

	/**
	 * @ignore
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
	 * This template method is called at the end of the constructor
	 */
	protected function init()
	{
		// override this method to initialize special PHP handling for an option
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
	 */
	public function init_styles()
	{
		// override this method to initialize special style handling for an option
	}

	/**
	 * This template method is called "just in time" to enqueue scripts
	 */
	public function init_scripts()
	{
		// override this method to initialize special script handling for an option
	}

	/**
	 * This method must be implemented to print the option's field HTML
	 *
	 * This method should be used for logic only. The renderer which is passed
	 * should be called to actually generate the markup when possible.
	 */
	abstract protected function render_field( Pie_Easy_Options_Option_Renderer $renderer );

	/**
	 * Toggle post override ON
	 *
	 * If enabled, post override will force the option to return it's value as set in POST
	 *
	 * @see disable_post_override
	 */
	public function enable_post_override()
	{
		$this->post_override = true;
	}

	/**
	 * Toggle post override OFF
	 *
	 * @see enable_post_override
	 */
	public function disable_post_override()
	{
		$this->post_override = false;
	}

	/**
	 * Get (read) the value of this option
	 *
	 * @see enable_post_override
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
	 * Check that current user has all required capabilities to view/edit this option
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
	 * Set the CSS class attribute to apply to this option's container element
	 *
	 * @param string $class
	 */
	public function set_class( $class )
	{
		$this->set_directive( 'class', $class );
	}

	/**
	 * Set the CSS id attribute to apply to the field of this option
	 *
	 * @param string $field_id
	 */
	public function set_field_id( $field_id )
	{
		$this->set_directive( 'field_id', $field_id );
	}

	/**
	 * Set the CSS class attribute to apply to the field of this option
	 *
	 * @param string $field_class
	 */
	public function set_field_class( $field_class )
	{
		$this->set_directive( 'field_class', $field_class );
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
			$this->set_directive( 'field_options', $field_options, true );
		}
	}

	/**
	 * Set an option that is required for this one to display
	 *
	 * @param string $option_name
	 */
	public function set_required_option( $option_name )
	{
		$this->set_directive( 'required_option', $option_name, true );
	}

	/**
	 * Set a feature that must be supported/enabled for this option to display
	 *
	 * @param string $feature_name
	 */
	public function set_required_feature( $feature_name )
	{
		$this->set_directive( 'required_feature', $feature_name, true );
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
		// use option's theme if empty
		$this->default_value_theme = empty( $theme ) ? $this->theme : $theme;

		// set the default value
		$this->set_directive( 'default_value', $value );

		return true;
	}

	/**
	 * Set additional capabilities which are required for this option to show
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
	 * Set the documentation file for this option
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
		return sprintf( self::PREFIX_TPL, $this->conf->get_api_slug() );
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
			// determine theme to use
			if ( $attach_id == $this->default_value ) {
				$theme = $this->default_value_theme;
			} else {
				$theme = $this->theme;
			}
			// mimic the src array
			$src[0] = Pie_Easy_Files::theme_file_url( $theme, $attach_id );
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

			// determine theme to use
			if ( $value == $this->default_value ) {
				$theme = $this->default_value_theme;
			} else {
				$theme = $this->theme;
			}

			// they must have provided an image path
			return Pie_Easy_Files::theme_file_url( $theme, $value );

		}

		return null;
	}
}

?>
