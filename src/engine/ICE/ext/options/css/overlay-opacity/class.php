<?php
/**
 * Infinity Theme: option extensions, CSS overlay opacity class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-extensions
 * @subpackage options
 * @since 1.0
 */

/**
 * CSS overlay opacity
 *
 * @package Infinity-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Css_Overlay_Opacity
	extends ICE_Ext_Option_Ui_Slider
{
	/**
	 * The name of an option which must not be empty in order for
	 * this option to render the dynamic styles
	 *
	 * @var string
	 */
	protected $linked_image;

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'linked_image':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 */
	protected function configure()
	{
		// set defaults first
		$this->title = __( 'Overlay Opacity', 'infinity-engine' );
		$this->description = __( 'Select the overlay opacity by moving the slider', 'infinity-engine' );
		$this->default_value = 0.2;
		$this->min = 0;
		$this->max = 1;
		$this->step = 0.01;
		$this->suffix = ' level';
		$this->style_property = 'opacity';

		// run parent
		parent::configure();

		// import settings
		$this->import_settings( array(
			'linked_image'
		));
	}

	/**
	 */
	public function inject_auto_style( ICE_Style $style )
	{
		// check linked image
		if ( true === $this->check_linked_image() ) {
			// good to go
			parent::inject_auto_style( $style );
		}
	}
	
	/**
	 * Check if linked image has been set and if it has a value.
	 *
	 * @return boolean
	 */
	protected function check_linked_image()
	{
		// linked image set?
		if ( $this->linked_image ) {
			// get it
			$ne_option = $this->policy()->registry()->get( $this->linked_image, $this->get_group() )->get();
			// return true if NOT empty
			return ( !empty( $ne_option ) );
		}

		// no linked image set
		return true;
	}
}
