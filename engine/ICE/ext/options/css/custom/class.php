<?php
/**
 * ICE API: option extensions, css class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

/**
 * CSS option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Css_Custom
	extends ICE_Ext_Option_Textarea
{
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// setup actions
		add_action( 'ice_init_blog', array( $this, 'setup_styles' ) );
	}

	/**
	 * Setup styles.
	 */
	public function setup_styles()
	{
		// dynamic styles
		$style = new ICE_Style( $this );
		$style->add_callback( 'custom', array( $this, 'inject_css' ) );
		// enqueue it
		ice_enqueue_style_obj( $style );
	}

	/**
	 */
	public function inject_css( $style )
	{
		// get value
		$value = trim( $this->get() );

		// did we get anything?
		if ( !empty( $value ) ) {
			// have to assume its valid CSS, add as string
			$style->add_string( 'css', $value );
		}
	}
}
