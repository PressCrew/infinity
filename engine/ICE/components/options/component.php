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

ICE_Loader::load(
	'base/component',
	'base/scheme'
);

/**
 * Make an option easy
 *
 * @package ICE-components
 * @subpackage options
 */
abstract class ICE_Option extends ICE_Component
{
	/**
	 * If true, a POST value will override the real option value
	 *
	 * @var boolean
	 */
	private $__post_override__ = false;

	/**
	 * Default value of the option
	 *
	 * @var mixed
	 */
	protected $default_value;

	/**
	 * The feature for which this option was created (slug)
	 *
	 * @var string
	 */
	protected $feature;

	/**
	 * The CSS class to apply to the option's input field
	 *
	 * @var string
	 */
	protected $field_class;

	/**
	 * The CSS id to apply to the option's input field
	 *
	 * @var string
	 */
	protected $field_id;

	/**
	 * An array of field options
	 *
	 * @var array
	 */
	protected $field_options;

	/**
	 * The section to which this options is assigned (slug)
	 *
	 * @var string
	 */
	protected $section;

	/**
	 * Used by options whose value is the value of an element style property
	 *
	 * @var string
	 */
	protected $style_property;

	/**
	 * Used by options which target css selectors
	 *
	 * @var string
	 */
	protected $style_selector;

	/**
	 * Used by options whose value is a unit of measure for an element style property
	 *
	 * @var string
	 */
	protected $style_unit;

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'default_value':
			case 'feature':
			case 'field_class':
			case 'field_id':
			case 'field_options':
			case 'section':
			case 'style_property':
			case 'style_selector':
			case 'style_unit':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 */
	protected function configure()
	{
		// set defaults
		$this->section = 'default';

		// run parent
		parent::configure();

		// import settings
		$this->import_settings( array(
			'default_value',
			'feature',
			'field_class',
			'field_id',
			'field_options',
			'section',
			'style_property',
			'style_selector',
			'style_unit'
		));

		// field options
		
		// skip loading field options outside of dashboard
		if ( !is_admin() ) {
			return;
		}
		
		// init temp field options array
		$field_options = array();
		
		// @todo this grew too big, move to private method
		if ( isset( $this->field_options ) ) {

			// is configured field options already an array?
			if ( is_array( $this->field_options ) ) {
				
				// yep, done!
				return;

			// is it a string?
			} elseif ( is_string( $this->field_options ) ) {

				// possibly a function
				$callback = $this->field_options;

				// check if the function exists
				if ( function_exists( $callback ) ) {
					// call it
					$field_options = $callback();
					// make sure we got an array
					if ( false === is_array( $field_options ) ) {
						throw new Exception( sprintf( 'The field options callback function "%s" did not return an array', $callback ) );
					}
				} else {
					throw new Exception( sprintf( 'The field options callback function "%s" does not exist', $callback ) );
				}

			} else {
				throw new Exception( sprintf( 'The field options for the "%s" option is not configured correctly', $this->get_name() ) );
			}
		
		} elseif ( $this instanceof ICE_Option_Auto_Field ) {

			// auto field can't overwrite existing options
			if ( null === $this->field_options ) {
				// call template method to load options
				$field_options = $this->load_field_options();
			}

		} else {

			// check type
			if (
				$this->style_selector &&
				$this->style_property &&
				(
					true === $this instanceof ICE_Ext_Option_Select ||
					true === $this instanceof ICE_Ext_Option_Radio
				)
			) {
				$style_prop = ICE_Style_Property_Factory::instance()->create( $this->style_property );
				$field_options = $style_prop->get_value_list();
			}

		}

		// make sure we ended up with some options
		if ( is_array( $field_options ) && count( $field_options ) >= 1 ) {
			// finally set them for the option
			$this->field_options = $field_options;
		}
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
		// get component name
		$name = $this->get_name();

		// post override?
		if (
			true === $this->__post_override__ &&
			true === isset( $_POST[ $name ] )
		) {
			// yep, return POST value
			return $_POST[ $name ];
		} else {
			// no, return real value from registry
			return
				$this->policy()->registry()->get_theme_mod(
					$name,
					$this->default_value
				);
		}
	}

	/**
	 */
	public function setup_auto_style()
	{
		// run parent
		parent::setup_auto_style();

		// have a style selector AND style property?
		if ( $this->style_selector && $this->style_property ) {
			// yep, handle dynamic styles
			$style = new ICE_Style( $this );
			$style->add_callback( 'auto_style', array( $this, 'inject_auto_style' ) );
			$style->enqueue();
		}
	}

	/**
	 * Inject style rule if this option has a value set.
	 *
	 * @param ICE_Style $style
	 */
	public function inject_auto_style( ICE_Style $style )
	{
		// determine value to get
		if ( $this instanceof ICE_Option_Attachment_Image ) {
			$value = $this->get_image_url( 'full' );
		} elseif ( $this instanceof ICE_Option_Static_Image ) {
			$value = $this->get_image_url();
		} else {
			$value = $this->get();
		}

		// is there are value set?
		if ( null !== $value ) {
			// yep, new rule
			$rule = $style->rule( 'first', $this->style_selector );
			// add style property
			$rule->add_property( $this->style_property, $value . $this->style_unit );
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

		// did we get an attachment id?
		if ( is_numeric( $attach_id ) ) {
			// is attach id gte one?
			if ( $attach_id >= 1 ) {
				// try to get the attachment info
				$src = wp_get_attachment_image_src( $attach_id, $size );
			} else {
				// id of zero or less is impossible to lookup,
				// return false immediately to avoid costly query.
				return false;
			}
		} else {
			// try to get default url
			$default_url = $this->get_default_image_url();
			// get anything?
			if ( $default_url ) {
				// mimic the src array
				$src = array_fill( 0, 3, null );
				// zero index is the url
				$src[0] = $default_url;
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

			// has default config?
			if ( isset( $this->default_value ) ) {
				// yep, locate theme
				$theme = ICE_Scheme::instance()->locate_theme( $this->default_value );
				// get a theme?
				if ( $theme ) {
					// yep, return URL of file
					return ICE_Scheme::instance()->theme_file_url( $theme, $this->default_value );
				}
			}
		}

		return null;
	}

	/**
	 * Return absolute URL of the default image (if set)
	 *
	 * @return string|false
	 */
	public function get_default_image_url()
	{
		// was a default set?
		if ( strlen( $this->default_value ) ) {
			// try to get theme
			$theme = ICE_Scheme::instance()->locate_theme( $this->default_value );
			// get a theme?
			if ( $theme ) {
				// yep, return URL of file
				return ICE_Scheme::instance()->theme_file_url( $theme, $this->default_value );
			}
		}

		// no default set
		return false;
	}
}
