<?php
/**
 * PIE API: screen renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage options
 * @since 1.0
 */

/**
 * Make rendering screens easy
 *
 * @package PIE
 * @subpackage screens
 */
abstract class Pie_Easy_Screens_Renderer extends Pie_Easy_Renderer
{
	/**
	 * Render the screen
	 */
	protected function render_output()
	{ ?>
		<div class="<?php $this->render_classes() ?>">
			<div class="<?php print esc_attr( $this->get_current()->class_title ) ?>">
				<h3><?php $this->render_title() ?></h3>
				<input name="save_section_<?php $this->render_name() ?>" type="submit" value="<?php _e( 'Save Changes', pie_easy_text_domain ) ?>" />
			</div>
			<div class="<?php print esc_attr( $this->get_current()->class_content ) ?>">
				<?php print $content ?>
			</div>
		</div><?php
	}
	
}

?>
