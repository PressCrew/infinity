<?php
/**
 * PIE API: option extensions, page class file
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
 * Page option
 *
 * @package PIE
 * @subpackage options-ext
 */
class Pie_Easy_Exts_Option_Page
	extends Pie_Easy_Options_Option
{
	/**
	 * Render a page select box
	 */
	public function render_field( Pie_Easy_Options_Option_Renderer $renderer )
	{
		$args = array(
			'depth'		=> 0,
			'child_of'	=> 0,
			'echo'		=> true,
			'selected'	=> $this->get(),
			'name'		=> $this->name );

		// call the WP function
		wp_dropdown_pages( $args );
	}
}

?>
