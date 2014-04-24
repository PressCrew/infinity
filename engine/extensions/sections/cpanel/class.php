<?php
/**
 * Infinity Theme: section extensions, cpanel section class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage extensions
 * @since 1.2
 */

/**
 * Cpanel section.
 *
 * @package Infinity
 * @subpackage extensions
 */
class Infinity_Ext_Section_Cpanel
	extends ICE_Section
{
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// setup dashboard styles
		add_action( 'ice_init_dash', array( $this, 'setup_dash_styles' ) );
	}

	/**
	 * Setup dashboard styles.
	 */
	public function setup_dash_styles()
	{
		// dynamic styles
		$style = new ICE_Style( $this );
		$style->add_file( 'admin', 'admin.css' );
		// enqueue it
		ice_enqueue_style_obj( $style );
	}
}
