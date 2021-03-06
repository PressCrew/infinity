<?php
/**
 * ICE API: option extensions, UI overlay picker class file
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
 * UI Overlay Picker
 *
 * This option is an extension of the image picker for handling image overlays
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Ui_Overlay_Picker
	extends ICE_Ext_Option_Ui_Image_Picker
{
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// setup styles
		add_action( 'ice_init_blog', array( $this, 'setup_styles' ) );
	}

	/**
	 * Setup styles.
	 */
	public function setup_styles()
	{
		// dynamic styles
		$style = new ICE_Style( $this );
		$style->add_callback( 'bgimage-gen', array( $this, 'bg_image_style' ) );
		$style->enqueue();
	}

	/**
	 */
	public function bg_image_style( $style )
	{
		// try to get my image url
		$url = $this->get_image_url();

		// have a url?
		if ( $url ) {
			// element rule
			$rule1 = $style->rule( 'container', $this->get_property( 'style_selector' ) );
			$rule1->ad( 'position', 'relative' );
			$rule1->ad( 'z-index', 110 );
			// pseudo element rule
			$rule2 = $style->rule( 'image', $this->get_property( 'style_selector' ) . ':before' );
			$rule2->ad( 'content', '""' );
			$rule2->ad( 'position', 'absolute' );
			$rule2->ad( 'z-index', -20 );
			$rule2->ad( 'top', 0 );
			$rule2->ad( 'right', 0 );
			$rule2->ad( 'left', 0 );
			$rule2->ad( 'bottom', 0 );
			$rule2->ad( 'background-image', sprintf( 'url("%s")', $url ) );
			$rule2->ad( 'display', 'block' );
		}
	}
}
