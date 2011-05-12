<?php
/**
 * PIE API: option extensions, colorpicker class file
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
 * Colorpicker option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Colorpicker
	extends Pie_Easy_Options_Option
{
	public function init_styles()
	{
		wp_enqueue_style( 'pie-easy-colorpicker' );
	}

	public function init_scripts()
	{
		wp_enqueue_script( 'pie-easy-colorpicker' );
	}

	/**
	 * Render a color picker input
	 *
	 * @see render_input
	 */
	public function render_field( Pie_Easy_Options_Option_Renderer $renderer )
	{
		// render the input text field
		$renderer->render_input( 'text' );

		// now the color picker box ?>
		<div id="pie-easy-options-cp-wrapper-<?php $renderer->render_name() ?>" class="pie-easy-options-cp-box">
			<div style="background-color: <?php print esc_attr( $this->get() ) ?>;"></div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				pieEasyColorPicker.init(
					'input[name=<?php $renderer->render_name() ?>]',
					'div#pie-easy-options-cp-wrapper-<?php $renderer->render_name() ?>'
				);
			});
		</script><?php
	}
}

?>
