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
		$this->policy()->uploader()->init_screen();
	}

	public function init_styles()
	{
		$this->policy()->uploader()->init_styles();
	}

	public function init_scripts()
	{
		$this->policy()->uploader()->init_scripts();
	}

	public function init_ajax()
	{
		$this->policy()->uploader()->init_ajax();
	}

	public function render_field()
	{
		$this->policy()->uploader()->render( $this, $this->policy() );
	}
}

?>
