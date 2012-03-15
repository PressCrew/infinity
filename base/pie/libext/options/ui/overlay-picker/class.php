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
	public function init_styles()
	{
		parent::init_styles();

		// add bg image style callback
		$this->style()->cache( 'bgimage-gen', 'bg_image_style' );
	}

	/**
	 */
	public function init_scripts()
	{
		parent::init_scripts();

		// add overlay script callback
		$this->script()->cache( 'overlay-gen', 'overlay_script' );
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
			$rule1 = $style->rule( $this->style_selector );
			$rule1->ad( 'position', 'relative' );
			$rule1->ad( 'z-index', 1 );
			// pseudo element rule
			$rule2 = $style->rule( $this->style_selector . ':before' );
			$rule2->ad( 'content', '""' );
			$rule2->ad( 'position', 'absolute' );
			$rule2->ad( 'z-index', -1 );
			$rule2->ad( 'top', 0 );
			$rule2->ad( 'right', 0 );
			$rule2->ad( 'left', 0 );
			$rule2->ad( 'bottom', 0 );
			$rule2->ad( 'background-image', sprintf( 'url("%s")', $url ) );
			// toggle on
			$this->__have_style__ = true;
		}
	}

	/**
	 */
	public function overlay_script( $script )
	{
		// have a style and selector?
		if ( $this->__have_style__ && $this->style_selector ) {

			// new logic capture
			$script->begin_logic();

			// generate script
			if (0): ?><script><?php endif; ?>

			// call the add overlay helper
			$('<?php print $this->style_selector ?>').pieEasyExtsAddOverlay('<?php print $this->get_element_id(); ?>', '<?php print $this->get_element_class(); ?>');
			
			<?php if (0): ?></script><?php endif;

			$logic = $script->end_logic();
			$logic->ready = true;
			$logic->alias = true;
		}
	}
}

?>
