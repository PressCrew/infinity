<?php
/**
 * PIE API: section renderer class file
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
 * Make rendering sections easy
 *
 * @package PIE
 * @subpackage options
 */
abstract class Pie_Easy_Sections_Renderer extends Pie_Easy_Renderer
{
	/**
	 * Render the section layout around the section's content
	 *
	 * If you override this method, make sure you include all of the CSS classes!
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

	/**
	 * Render wrapper classes
	 */
	public function render_classes()
	{
		printf(
			'%1$s %1$s-%2$s',
			esc_attr( $this->get_current()->class ),
			esc_attr( $this->get_current()->name )
		);
	}

}

?>
