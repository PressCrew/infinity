<?php
/**
 * PIE API: option extensions, colorpicker class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

/**
 * Colorpicker option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Colorpicker
	extends Pie_Easy_Options_Option
{
	public function init_styles()
	{
		parent::init_styles();
		wp_enqueue_style( 'pie-easy-colorpicker' );
	}

	public function init_scripts()
	{
		parent::init_scripts();
		wp_enqueue_script( 'pie-easy-colorpicker' );
	}

	/**
	 * Render a color picker input
	 *
	 * @see render_input
	 */
	public function render_field()
	{
		// render the input text field
		$this->policy()->renderer()->render_input( 'text' );

		// now the color picker box ?>
		<div id="pie-easy-options-cp-wrapper-<?php $this->policy()->renderer()->render_name() ?>" class="pie-easy-options-cp-box">
			<div style="background-color: <?php print esc_attr( $this->get() ) ?>;"></div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				pieEasyColorPicker.init(
					'input[name=<?php $this->policy()->renderer()->render_name() ?>]',
					'div#pie-easy-options-cp-wrapper-<?php $this->policy()->renderer()->render_name() ?>'
				);
			});
		</script><?php
	}
}

?>
