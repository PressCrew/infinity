<?php
/**
 * PIE API: option extensions, uploader class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options-ext
 * @since 1.0
 */

/**
 * Uploader option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Upload
	extends Pie_Easy_Options_Option_Image
{
	public function init()
	{
		// always run parent first
		parent::init();

		// upload files is required
		$this->add_capabilities( 'upload_files' );
	}

	public function init_screen()
	{
		$this->conf->get_options_uploader()->init_screen();
	}

	public function init_ajax()
	{
		$this->conf->get_options_uploader()->init_ajax();
	}

	public function render_field( Pie_Easy_Options_Option_Renderer $renderer )
	{
		$this->conf->get_options_uploader()->render( $this, $renderer );
	}
}

?>
