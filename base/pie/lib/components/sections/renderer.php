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
	 * Render the title class
	 */
	public function render_class_title( $class = null )
	{
		$classes = $this->merge_classes( $class, $this->component()->class_title );

		if ( $classes ) {
			print esc_attr( $classes );
		}
	}

	/**
	 * Render the content class
	 */
	public function render_class_content( $class = null )
	{
		$classes = $this->merge_classes( $class, $this->component()->class_content );

		if ( $classes ) {
			print esc_attr( $classes );
		}
	}

}

?>
