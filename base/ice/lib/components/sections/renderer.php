<?php
/**
 * ICE API: section renderer class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-components
 * @subpackage options
 * @since 1.0
 */

/**
 * Make rendering sections easy
 *
 * @package ICE-components
 * @subpackage sections
 */
abstract class ICE_Section_Renderer extends ICE_Renderer
{
	/**
	 * Render the title class
	 */
	public function render_class_title()
	{
		if ( $this->component()->class_title ) {
			print esc_attr( $this->component()->class_title );
		}
	}

	/**
	 * Render the content class
	 */
	public function render_class_content()
	{
		if ( $this->component()->class_content ) {
			print esc_attr( $this->component()->class_content );
		}
	}

}

?>
