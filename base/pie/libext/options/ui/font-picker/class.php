<?php
/**
 * PIE API: option extensions, UI font picker class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'utils/webfont' );
Pie_Easy_Loader::load_ext( 'options/ui/scroll-picker' );

/**
 * UI Font Picker
 *
 * This option is an extension of the scroll picker for handling font selection
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Ui_Font_Picker
	extends Pie_Easy_Exts_Options_Ui_Scroll_Picker
{
	/**
	 *
	public function configure( Pie_Easy_Init_Config $config )
	{
		// file directory
		if ( isset( $config->file_directory ) ) {
			$this->file_directory = $config->file_directory;
		}

		// file directory
		if ( isset( $config->file_extension ) ) {
			$this->file_extension = $config->file_extension;
		}

		// run parent
		parent::configure( $config );
	}
	 */

	public function init_styles()
	{
		parent::init_styles();

		if ( is_admin() ) {
			wp_enqueue_style( 'jquery-multiselect' );
		}
	}

	public function init_scripts()
	{
		parent::init_scripts();

		if ( is_admin() ) {
			wp_enqueue_script( 'jquery-multiselect' );
			wp_enqueue_script( 'jquery-pie-ui-fontpicker' );
		}
	}

	/**
	 */
	public function get_template_vars()
	{
		// get parent vars
		$parent_vars = parent::get_template_vars();

		// build up local vars
		$local_vars = array(
			'webfont_url' => Pie_Easy_Webfont::instance(0)->url
		);

		// return parent and local vars merged
		return array_merge( $parent_vars, $local_vars );
	}
}

?>
