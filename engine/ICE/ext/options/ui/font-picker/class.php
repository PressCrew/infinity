<?php
/**
 * ICE API: option extensions, UI font picker class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load( 'utils/webfont' );

/**
 * UI Font Picker
 *
 * This option is an extension of the scroll picker for handling font selection
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Ext_Option_Ui_Font_Picker
	extends ICE_Ext_Option_Ui_Scroll_Picker
{
	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// setup dashboard assets
		add_action( 'ice_init_dash', array( $this, 'setup_dash_assets' ) );
	}

	/**
	 * Setup dashboard assets.
	 */
	public function setup_dash_assets()
	{
		// dependancies
		ice_enqueue_style( 'jquery-multiselect' );
		ice_enqueue_script( 'jquery-multiselect' );

		// dynamic styles
		$style = new ICE_Style( $this );
		$style->add_file( 'admin', 'admin.css' );
		$style->enqueue();

		// dynamic scripts
		$script = new ICE_Script( $this );
		$script->add_file( 'admin', 'admin.js' );
		$script->enqueue();
	}

	/**
	 */
	public function get_template_vars()
	{
		// get parent vars
		$parent_vars = parent::get_template_vars();

		// build up local vars
		$local_vars = array(
			'webfont_url' => ICE_Webfont::instance(0)->url
		);

		// return parent and local vars merged
		return array_merge( $parent_vars, $local_vars );
	}
}
