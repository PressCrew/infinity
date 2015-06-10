<?php
/**
 * ICE API: option extensions, css background image class file
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
 * CSS background image option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Css_Bg_Image
	extends ICE_Ext_Option_Upload
{
	/**
	 */
	protected function configure()
	{
		// set defaults first
		$this->title = __( 'Background Image', 'infinity-engine' );
		$this->description = __( 'Upload an image to use as the background', 'infinity-engine' );
		$this->style_property = 'background-image';

		// run parent
		parent::configure();
	}

	public function init()
	{
		// run parent
		parent::init();

		// enqueue bg image override
		add_action( 'ice_init_blog', array( $this, 'bg_image_override' ) );
	}

	/**
	 * Add a rule to kill any background image that might be set,
	 * if the background image was explicitly disabled (zero value).
	 */
	public function bg_image_override()
	{
		// get current value
		$value = $this->get();

		// is value a literal zero?
		if ( is_numeric( $value ) && 0 === (integer) $value ) {
			// dynamic styles
			$style = new ICE_Style( $this );
			$rule = $style->rule( 'bg', $this->get_property( 'style_selector' ) );
			$rule->add_declaration( 'background-image', 'none' );
			// enqueue it
			$style->enqueue();
		}
	}
}
