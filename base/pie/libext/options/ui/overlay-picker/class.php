<?php
/**
 * PIE API: option extensions, UI overlay picker class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/ui/image-picker' );

/**
 * UI Overlay Picker
 *
 * This option is an extension of the image picker for handling image overlays
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Ui_Overlay_Picker
	extends Pie_Easy_Exts_Options_Ui_Image_Picker
{
	/**
	 * @var boolean
	 */
	private $__have_style__;

	/**
	 */
	public function init_styles_dynamic()
	{
		// run parent
		parent::init_styles_dynamic();

		// try to get my image url
		$url = $this->get_image_url();

		// have a url?
		if ( $url ) {
			// new style rule
			$rule = $this->style()->rule( '#' . $this->get_element_id() );
			// set background on my class
			$rule->ad( 'background-image', sprintf( 'url("%s")', $url ) );
			// toggle on
			$this->__have_style__ = true;
		}
	}

	public function init_scripts_dynamic()
	{
		// run parent
		parent::init_scripts_dynamic();

		// have a style and selector?
		if ( $this->__have_style__ && $this->style_selector ) {

			// new logic capture
			$this->script()->begin_logic();

			// generate script
			if (0): ?><script><?php endif; ?>

			// call the add overlay helper
			$('<?php print $this->style_selector ?>').pieEasyExtsAddOverlay('<?php print $this->get_element_id(); ?>', '<?php print $this->get_element_class(); ?>');
			
			<?php if (0): ?></script><?php endif;

			$logic = $this->script()->end_logic();
			$logic->ready = true;
			$logic->alias = true;
		}
	}
}

?>
