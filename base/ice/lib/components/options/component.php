<?php
/**
 * ICE API: options option class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load( 'base', 'utils/docs', 'schemes' );

/**
 * Make an option easy
 *
 * @package ICE-components
 * @subpackage options
 * @property-read string $section The section to which this options is assigned (slug)
 * @property-read string $feature The feature for which this option was created (slug)
 * @property-read string $field_id The CSS id to apply to the option's input field
 * @property-read string $field_class The CSS class to apply to the option's input field
 * @property-read array $field_options An array of field options
 * @property-read mixed $default_value Default value of the option
 * @property-read string $style_selector Used by options which target css selectors
 * @property-read string $style_property Used by options whose value is the value of an element style property
 * @property-read string $style_unit Used by options whose value is a unit of measure for an element style property
 * @property-read string $style_section Which section to export any dynamic styles to
 */
abstract class ICE_Option extends ICE_Component
{
	/**
	 * All global options are prepended with this prefix template
	 */
	const PREFIX_TPL = '%s_opt_';

	/**
	 * The string on which to split field option key => values
	 */
	const FIELD_OPTION_DELIM = '=';

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
	 * Local style property object
	 *
	 * @var ICE_Style_Property
	 */
	private $__style_property__ = false;

	/**
	 */
	protected function init()
	{
		parent::init();

		// init directives
		$this->section = 'default';
		$this->feature = null;
		$this->default_value = null;
		$this->field_id = null;
		$this->field_class = null;
		$this->field_options = null;
		$this->style_selector = null;
		$this->style_property = null;
		$this->style_unit = null;
		$this->style_section = null;

		// user must be allowed to manage options
		$this->add_capabilities( 'manage_options' );
	}

	/**
	 */
	public function init_styles()
	{
		parent::init_styles();
		$this->refresh_style_property();
		$this->generate_style_property();
	}

	/**
	 */
	public function configure()
	{
		// RUN PARENT FIRST!
		parent::configure();

		// get config
		$config = $this->config();

		// section
		if ( isset( $config->section ) ) {
			$this->set_section( $config->section );
		}

		// feature
		if ( isset( $config->feature ) ) {
			$this->feature = $config->feature;
		}

		// default value
		if ( isset( $config->default_value ) ) {
			$this->default_value = $config->default_value;
		}

		// css id
		if ( isset( $config->field_id ) ) {
			$this->field_id = $config->field_id;
		}

		// css class
		if ( isset( $config->field_class ) ) {
			$this->field_class = $config->field_class;
		}

		// style selector
		if ( isset( $config->style_selector ) ) {
			$this->style_selector = $config->style_selector;
		}

		// style property
		if ( isset( $config->style_property ) ) {
			$this->style_property = $config->style_property;
		}
		
		// style unit
		if ( isset( $config->style_unit ) ) {
			$this->style_unit = $config->style_unit;
		}

		// style section
		if ( isset( $config->style_section ) ) {
			$this->style_section = $config->style_section;
		}

		// setup style property object
		$this->refresh_style_property();

		// field options
		// @todo this grew too big, move to private method
		if ( isset( $config->field_options ) ) {

			if ( $config->field_options instanceof ICE_Map ) {

				// loop through all field options
				foreach ( $config->field_options as $field_option ) {
					// split each one at the delimeter
					$field_option = explode( self::FIELD_OPTION_DELIM, $field_option, 2 );
					// add to array
					$field_options[trim($field_option[0])] = trim($field_option[1]);
				}

			} elseif ( strlen( $config->field_options ) ) {

				// possibly a function
				$callback = $config->field_options;

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
		
		} elseif ( $this instanceof ICE_Option_Auto_Field ) {

			// skip if already populated
			if ( $this->field_options == null ) {
				// call template method to load options
				$field_options = $this->load_field_options();
			}

		} elseif ( $this->__style_property__ ) {

			// check type
			switch ( true ) {
				case ( $this instanceof ICE_Ext_Option_Select ):
				case ( $this instanceof ICE_Ext_Option_Radio ):
					$field_options = $this->__style_property__->get_list_values();
			}

		}

		// make sure we ended up with some options
		if ( isset( $field_options ) && count( $field_options ) >= 1 ) {
			// finally set them for the option
			$this->directive( 'field_options', $field_options, true, true );
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
			return $this->get_option();
		}
	}

	/**
	 * Get (read) the value of this option from the database
	 *
	 * @return mixed
	 */
	protected function get_option()
	{
		return get_option( $this->get_api_name(), $this->default_value );
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
			// force numeric values to floats since it could be an int or a float
			if ( is_numeric( $value ) ) {
				$value = floatval( $value );
			}
			// is the value null, an empty string, or equal to the default value?
			if ( $value === null || $value === '' || $value === $this->default_value ) {
				// its pointless to store this option
				// try to delete it in case it already exists
				return $this->delete();
			} else {
				// create or update it
				if ( $this->update_option( $value ) ) {
					$this->update_meta( self::META_TIME_UPDATED, time() );
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Update the real option in the database
	 *
	 * @param mixed $value New option value
	 * @return boolean
	 */
	protected function update_option( $value )
	{
		return update_option( $this->get_api_name(), $value );
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
			if ( $this->delete_option() ) {
				$this->delete_meta();
				return true;
			}
		}

		return false;
	}

	/**
	 * Delete the option from the database
	 *
	 * @return boolean
	 */
	protected function delete_option()
	{
		return delete_option( $this->get_api_name() );
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
	protected function set_section( $section )
	{
		// lookup the section registry
		$section_registry = ICE_Policy::instance('ICE_Section_Policy')->registry();

		// get section from section registry
		$section = $section_registry->get( $section );

		// adding options to parent sections is not allowed
		foreach ( $section_registry->get_all() as $section_i ) {
			if ( $section->is_parent_of( $section_i ) ) {
				throw new Exception(
					sprintf( 'Cannot add options to section "%s" because it is acting as a parent section', $section->name ) );
			}
		}

		$this->section = $section->name;
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

	/**
	 * @todo need to get rid of this mess once fields component is working
	 */
	private function refresh_style_property()
	{
		// set to null in case it should not exist anymore
		$this->__style_property__ = null;

		// must have selector and property
		if ( $this->style_selector && $this->style_property ) {
			// setup property object
			$this->__style_property__ =
				ICE_Style_Property_Factory::instance()->create( $this->style_property );
		}
	}

	/**
	 */
	private function generate_style_property()
	{
		if ( $this->__style_property__ ) {

			// determine value to set
			if ( $this instanceof ICE_Option_Attachment_Image ) {
				$value = $this->get_image_url( 'full' );
			} elseif ( $this instanceof ICE_Option_Static_Image ) {
				$value = $this->get_image_url();
			} else {
				$value = $this->get();
			}

			// try to set the value
			if ( !is_null( $value ) && $this->__style_property__->set_value( $value, $this->style_unit ) ) {

				// get the style value
				$style_value = $this->__style_property__->get_value();

				// add value to component styles if set
				if ( isset( $style_value->value ) ) {
					if ( $this->style_section ) {
						$rule = $this->style()->section($this->style_section)->rule( $this->style_selector );
					} else {
						$rule = $this->style()->rule( $this->style_selector );
					}
					$rule->add_declaration(
						$this->__style_property__->name,
						$this->__style_property__->get_value()->format()
					);
				}
			}

		}
	}
}

/**
 * Interface to implement if the option defines its own field_options internally
 *
 * @package ICE-components
 * @subpackage options
 */
interface ICE_Option_Auto_Field
{
	/**
	 * Generate custom field options
	 *
	 * @return array of field options in [value] => [description] format
	 */
	public function load_field_options();
}

/**
 * Interface to implement if the option is referencing a static image
 *
 * @package ICE-components
 * @subpackage options
 */
interface ICE_Option_Static_Image
{
	/**
	 * Return the URL of a static image for this option
	 *
	 * @return string|false absolute URL to image file
	 */
	public function get_image_url();
}

/**
 * Interface to implement if the option is storing an image attachment id
 *
 * @package ICE-components
 * @subpackage options
 */
interface ICE_Option_Attachment_Image
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
	 * @return array|false Attachment meta data
	 */
	public function get_image_src( $size = 'thumbnail', $attach_id = null );

	/**
	 * Return the URL of an image attachment for this option
	 *
	 * This method is only useful if the option is storing the id of an attachment
	 *
	 * @param string $size Either a string (`thumbnail`, `medium`, `large` or `full`), or a two item array representing width and height in pixels, e.g. array(32,32). The size of media icons are never affected.
	 * @return string|false absolute URL to image file
	 */
	public function get_image_url( $size = 'thumbnail' );
}

/**
 * An option for storing an image (via WordPress attachment API)
 *
 * @package ICE-components
 * @subpackage options
 */
abstract class ICE_Option_Image
	extends ICE_Option
		implements ICE_Option_Attachment_Image
{
	/**
	 */
	public function get_image_src( $size = 'thumbnail', $attach_id = null )
	{
		// attach id was passed?
		if ( empty( $attach_id ) ) {
			$attach_id = $this->get();
		}

		// src is null by default
		$src = null;

		// did we get an attachement id?
		if ( is_numeric( $attach_id ) ) {
			// try to get the attachment info
			$src = wp_get_attachment_image_src( $attach_id, $size );
		} else {
			// was a default set?
			if ( isset( $this->default_value ) ) {
				// use default
				$directive = $this->directive()->get( 'default_value' );
				// mimic the src array
				$src = array_fill( 0, 3, null );
				// is a default set?
				if ( $directive->value ) {
					$src[0] = ICE_Files::theme_file_url( $directive->theme, $directive->value );
				}
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
			$directive = $this->directive()->get( 'default_value' );

			// they must have provided an image path
			return ICE_Files::theme_file_url( $directive->theme, $directive->value );

		}

		return null;
	}
}

?>
