<?php
/**
 * PIE API: section renderer class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-components
 * @subpackage options
 * @since 1.0
 */

/**
 * Make rendering sections easy
 *
 * @package PIE-components
 * @subpackage sections
 */
abstract class Pie_Easy_Sections_Renderer extends Pie_Easy_Renderer
{
	/**
	 * Render the section layout
	 *
	 * If you override this method, make sure you include all of the CSS classes!
	 */
	protected function render_output()
	{
		$this->get_current()->load_template();
	}

	/**
	 * Render the title class
	 */
	public function render_class_title( $class = null )
	{
		$classes = $this->merge_classes( $class, $this->get_current()->class_title );

		if ( $classes ) {
			print esc_attr( $classes );
		}
	}

	/**
	 * Render the content class
	 */
	public function render_class_content( $class = null )
	{
		$classes = $this->merge_classes( $class, $this->get_current()->class_content );

		if ( $classes ) {
			print esc_attr( $classes );
		}
	}

}

?>
